<?php
require_once dirname(__DIR__) . '/db.php';
require_once 'config.php';

$error_message = "We're sorry, but your payment could not be processed. Please try again or choose a different payment method.";
$transaction_status = 'UNKNOWN_FAILURE';

if (isset($_GET['data'])) {
    $esewa_response = json_decode(base64_decode($_GET['data']), true);

    if ($esewa_response && isset($esewa_response['signature']) && verifyEsewaResponseSignature($esewa_response)) {
        $transaction_uuid = $esewa_response['transaction_uuid'];
        $transaction_status = $esewa_response['status'] ?? 'FAILED';
        
        // Update the transaction status in the database
        updateEsewaTransactionStatus($transaction_uuid, $transaction_status, json_encode($esewa_response));

        // Set a more specific error message
        switch ($transaction_status) {
            case 'CANCELED':
                $error_message = 'Your payment was canceled. Please try again.';
                break;
            case 'NOT_FOUND':
                $error_message = 'The transaction was not found. It may have expired.';
                break;
            default:
                $error_message = 'Your payment failed. Please try again or contact support.';
                break;
        }
    } else if ($esewa_response) {
        // Log invalid signature
        error_log("eSewa failure response with invalid signature: " . json_encode($esewa_response));
        if (isset($esewa_response['transaction_uuid'])) {
            updateEsewaTransactionStatus($esewa_response['transaction_uuid'], 'SIGNATURE_FAILED');
        }
        $error_message = "There was an issue verifying the payment response. Please contact support.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Watch Cart</title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .failure-container {
            max-width: 600px;
            margin: 2rem;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .failure-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .failure-message {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .btn-retry {
            display: inline-block;
            padding: 1rem 2rem;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-retry:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="failure-container">
        <div class="failure-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <h1>Payment Failed</h1>
        <p class="failure-message"><?php echo htmlspecialchars($error_message); ?></p>
        <a href="../checkout.php" class="btn-retry">Return to Checkout</a>
    </div>
</body>
</html> 