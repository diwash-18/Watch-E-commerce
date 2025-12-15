<?php
require_once 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Log POST data
    error_log("POST data received: " . print_r($_POST, true));
    
    // Get and sanitize form data
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $message = sanitize_input($_POST['message']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?status=error&message=Invalid email format");
        exit();
    }
    
    try {
        // Check database connection
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
        
        // Prepare SQL statement
        $sql = "INSERT INTO contact_messages (name, email, phone, message, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Send email notification to admin
            $to = "info@wrist-wonders.com";
            $subject = "New Contact Form Submission";
            $email_message = "Name: " . $name . "\n";
            $email_message .= "Email: " . $email . "\n";
            $email_message .= "Phone: " . $phone . "\n\n";
            $email_message .= "Message:\n" . $message;
            
            $headers = "From: " . $email . "\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";
            
            mail($to, $subject, $email_message, $headers);
            
            // Redirect with success message
            header("Location: index.php?status=success&message=Thank you for your message. We will get back to you soon!");
            exit();
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Contact form error: " . $e->getMessage());
        // Redirect with error message
        header("Location: index.php?status=error&message=Sorry, there was an error sending your message. Please try again later.");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
} else {
    // If not POST request, redirect to home page
    header("Location: index.php");
    exit();
}

$conn->close();
?> 