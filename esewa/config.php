<?php
// eSewa API Configuration
define('ESEWA_MERCHANT_ID', 'EPAYTEST'); // Your eSewa Merchant ID
define('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q'); // Your eSewa Secret Key

// API Endpoints
define('ESEWA_API_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form');
define('ESEWA_VERIFICATION_URL', 'https://rc.esewa.com.np/api/epay/transaction/status/');

// Function to verify eSewa response signature
function verifyEsewaResponseSignature($response) {
    if (!isset($response['signature']) || !isset($response['signed_field_names'])) {
        return false;
    }

    $signed_fields = explode(',', $response['signed_field_names']);
    $signature_data = '';
    
    foreach ($signed_fields as $field) {
        if (isset($response[$field])) {
            if (!empty($signature_data)) {
                $signature_data .= ',';
            }
            $signature_data .= $field . '=' . $response[$field];
        }
    }

    $calculated_signature = base64_encode(hash_hmac('sha256', $signature_data, ESEWA_SECRET_KEY, true));
    return hash_equals($response['signature'], $calculated_signature);
}

// Function to get eSewa Merchant ID
function getEsewaMerchantId() {
    return ESEWA_MERCHANT_ID;
}

// Function to check transaction status with eSewa
function checkEsewaTransactionStatus($merchant_id, $product_code, $total_amount, $transaction_uuid) {
    $url = ESEWA_VERIFICATION_URL . "?product_code={$product_code}&total_amount={$total_amount}&transaction_uuid={$transaction_uuid}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($error) {
        error_log("eSewa verification cURL Error: " . $error . ", HTTP code: " . $http_code);
        return [
            'curl_error' => $error,
            'http_code' => $http_code,
            'raw_response' => $response
        ];
    }
    
    $decoded = json_decode($response, true);
    if ($decoded === null) {
        error_log("eSewa verification API returned invalid JSON. HTTP code: " . $http_code . ", Raw response: " . $response);
        return [
            'json_error' => 'Invalid JSON',
            'http_code' => $http_code,
            'raw_response' => $response
        ];
    }
    
    return $decoded;
}

// Function to get transaction from database
function getEsewaTransaction($transaction_uuid) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM esewa_transactions WHERE transaction_uuid = ?");
    $stmt->bind_param("s", $transaction_uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// Function to update transaction status
function updateEsewaTransactionStatus($transaction_uuid, $status, $response_data = null) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE esewa_transactions SET status = ?, response_data = ?, updated_at = NOW() WHERE transaction_uuid = ?");
    $stmt->bind_param("sss", $status, $response_data, $transaction_uuid);
    
    return $stmt->execute();
} 