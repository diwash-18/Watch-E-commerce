<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once dirname(__DIR__) . '/db.php';
require_once __DIR__ . '/config.php';

if (!isset($_GET['data'])) {
    die("Invalid request.");
}

$esewa_response = json_decode(base64_decode($_GET['data']), true);

if (!$esewa_response || !isset($esewa_response['signature'])) {
    die("Invalid response from eSewa.");
}

// Verify the signature of the response from eSewa
if (!verifyEsewaResponseSignature($esewa_response)) {
    // The signature from eSewa is tampered.
    // Log this event and show an error.
    error_log("eSewa signature verification failed. Response: " . json_encode($esewa_response));
    
    if (isset($esewa_response['transaction_uuid'])) {
        updateEsewaTransactionStatus($esewa_response['transaction_uuid'], 'SIGNATURE_FAILED');
    }

    header("Location: ../checkout.php?error=invalid_signature");
    exit();
}

$transaction_uuid = $esewa_response['transaction_uuid'];
$total_amount = $esewa_response['total_amount'];
$transaction_code = $esewa_response['transaction_code']; // eSewa's reference code

// Get our transaction from the database
$transaction = getEsewaTransaction($transaction_uuid);
if (!$transaction) {
    error_log("Could not find corresponding transaction in DB for UUID: " . $transaction_uuid);
    header("Location: ../checkout.php?error=transaction_not_found");
    exit();
}

// Verify transaction with eSewa's server
$product_code = "EPAYTEST"; // or fetch dynamically if needed
$status_response = checkEsewaTransactionStatus(getEsewaMerchantId(), $product_code, $total_amount, $transaction_uuid);

// Log the full status response for debugging
error_log('[eSewa Debug] Status response for transaction_uuid ' . $transaction_uuid . ': ' . json_encode($status_response));

if ($status_response && isset($status_response['status'])) {
    
    $conn->begin_transaction();
    try {
        // Update transaction status in our database
        updateEsewaTransactionStatus($transaction_uuid, $status_response['status'], json_encode($status_response));

        if ($status_response['status'] === 'COMPLETE') {
            // Verify the amount matches what we have stored
            if (floatval($status_response['total_amount']) != floatval($transaction['amount'])) {
                throw new Exception("Amount mismatch in transaction verification. Expected: " . $transaction['amount'] . ", Got: " . $status_response['total_amount']);
            }

            // Fallback: If user_id is missing, fetch from order
            $user_id = $transaction['user_id'];
            if (empty($user_id) && !empty($transaction['order_id'])) {
                $order_stmt = $conn->prepare("SELECT user_id FROM orders WHERE id = ?");
                $order_stmt->bind_param("i", $transaction['order_id']);
                $order_stmt->execute();
                $order_result = $order_stmt->get_result();
                if ($order_row = $order_result->fetch_assoc()) {
                    $user_id = $order_row['user_id'];
                    error_log("[eSewa Success] Fallback: user_id fetched from order: " . $user_id);
                } else {
                    error_log("[eSewa Success] Fallback failed: Could not fetch user_id from order for order_id: " . $transaction['order_id']);
                }
            }

            // Final fallback: try session user_id
            if (empty($user_id) && isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                error_log("[eSewa Success] Final fallback: user_id fetched from session: " . $user_id);
            }

            if (empty($user_id)) {
                $conn->rollback();
                error_log("[eSewa Success] ERROR: user_id is missing for transaction_uuid: " . $transaction_uuid);
                echo '<script>alert("Error: Could not determine your user account for cart clearing. Please contact support."); window.location.href = "../shop.php";</script>';
                exit();
            }

            // Update order status
            $stmt = $conn->prepare("UPDATE orders SET 
                payment_status = 'paid', 
                payment_method = 'esewa', 
                transaction_id = ?,
                payment_date = NOW()
                WHERE id = ?");
            $stmt->bind_param("si", $transaction_code, $transaction['order_id']);
            $stmt->execute();
            
            // Explicitly query the esewa_transactions table to ensure it is accessed on payment success
            $esewa_transaction_check = $conn->prepare("SELECT * FROM esewa_transactions WHERE transaction_uuid = ?");
            $esewa_transaction_check->bind_param("s", $transaction_uuid);
            $esewa_transaction_check->execute();
            $esewa_transaction_result = $esewa_transaction_check->get_result();
            if (!$esewa_transaction_result || $esewa_transaction_result->num_rows === 0) {
                // Log or handle the error if the transaction is not found
                error_log("[eSewa Success] Transaction not found in esewa_transactions for UUID: " . $transaction_uuid);
                // Optionally, you could show an error or take other action here
            }

            // Clear transaction session if it exists (it shouldn't be used, but as a cleanup)
            if (isset($_SESSION['esewa_transaction'])) {
                unset($_SESSION['esewa_transaction']);
            }

            // Clear the cart after successful payment (database)
            error_log("About to delete cart for user_id = " . $user_id);
            $conn->begin_transaction();
            try {
                $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $clear_cart->bind_param("i", $user_id);
                if (!$clear_cart->execute()) {
                    error_log("Failed to clear cart: " . $clear_cart->error);
                    throw new Exception("Cart deletion failed: " . $clear_cart->error);
                } else {
                    error_log("Cart cleared: " . $clear_cart->affected_rows . " rows deleted for user_id: $user_id");
                }
                // Also clear any cart-related session variable (future-proofing)
                if (isset($_SESSION['cart'])) {
                    unset($_SESSION['cart']);
                }
                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                error_log("Error during cart clear: " . $e->getMessage());
                echo '<script>alert("Error: Could not clear your cart after payment. Please contact support."); window.location.href = "../shop.php";</script>';
                exit();
            }

            $conn->commit();
            
            // Get order details for display
            $order_query = $conn->prepare("SELECT o.*, u.name as customer_name 
                                         FROM orders o 
                                         JOIN users u ON o.user_id = u.id 
                                         WHERE o.id = ?");
            $order_query->bind_param("i", $transaction['order_id']);
            $order_query->execute();
            $order = $order_query->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Watch Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .success-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .success-icon {
            color: #00b894;
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: scaleUp 0.5s ease-out;
        }

        @keyframes scaleUp {
            0% {
                transform: scale(0);
            }
            70% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        h1 {
            color: #2d3436;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        .payment-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: left;
        }

        .payment-details p {
            margin: 0.5rem 0;
            color: #636e72;
        }

        .payment-details strong {
            color: #2d3436;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: #00b894;
            color: white;
        }

        .btn-secondary {
            background: #0984e3;
            color: white;
        }

        .redirect-message {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #636e72;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Payment Successful!</h1>
        <p>Thank you for your payment. Your transaction has been completed successfully.</p>
        
        <div class="payment-details">
            <p><strong>Order ID:</strong> #<?php echo $transaction['order_id']; ?></p>
            <p><strong>Amount Paid:</strong> Rs. <?php echo number_format($total_amount, 2); ?></p>
            <p><strong>Transaction ID:</strong> <?php echo $transaction_code; ?></p>
            <p><strong>Payment Method:</strong> eSewa</p>
            <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a'); ?></p>
        </div>

        <div class="buttons">
            <a href="../order_success.php?order_id=<?php echo $transaction['order_id']; ?>" class="btn btn-primary">View Order Details</a>
            <a href="../shop.php" class="btn btn-secondary">Continue Shopping</a>
        </div>

        <p class="redirect-message">You will be redirected to the order details page in <span id="countdown">5</span> seconds...</p>
    </div>

    <script>
        // Countdown and redirect
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = '../order_success.php?order_id=<?php echo $transaction['order_id']; ?>';
            }
        }, 1000);
    </script>
</body>
</html>
<?php
            exit();
        } else {
            // Handle other statuses
            $conn->commit(); // Commit the status update
            $error_map = [
                'PENDING' => 'payment_pending',
                'CANCELED' => 'payment_canceled',
                'FAILED' => 'payment_failed',
            ];
            $error = $error_map[$status_response['status']] ?? 'payment_unknown_status';
            header("Location: ../checkout.php?error=" . $error);
            exit();
        }
    } catch (Exception $e) {
        $conn->rollback();
        error_log("eSewa payment processing error: " . $e->getMessage());
        header("Location: ../checkout.php?error=payment_processing_failed");
        exit();
    }
} else {
    // Log the failed transaction verification
    error_log("eSewa payment verification failed. Response: " . json_encode($status_response));
    updateEsewaTransactionStatus($transaction_uuid, 'VERIFICATION_FAILED');
    echo '<script>alert("Payment verification failed. Please contact support or try again.\n\nDebug: ' . htmlspecialchars(json_encode($status_response)) . '"); window.location.href = "../checkout.php?error=payment_verification_failed";</script>';
    exit();
}
// If status is not COMPLETE, log and show error
if ($status_response['status'] !== 'COMPLETE') {
    $conn->commit(); // Commit the status update
    error_log('[eSewa Debug] Payment not complete. Status: ' . $status_response['status'] . ', Response: ' . json_encode($status_response));
    echo '<script>alert("Your payment could not be confirmed as successful. Status: ' . htmlspecialchars($status_response['status']) . '. Please contact support if money was deducted.\n\nDebug: ' . htmlspecialchars(json_encode($status_response)) . '"); window.location.href = "../checkout.php?error=payment_not_complete";</script>';
    exit();
}
?> 