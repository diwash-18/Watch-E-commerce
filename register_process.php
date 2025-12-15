<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["fullname"]; // Changed from "name" to "fullname" to match form
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    
    // Check if email already exists
    $check = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='register.php';</script>";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (name, email, password, gender, dob) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password, $gender, $dob);

        if ($stmt->execute()) {
            // Get the user's ID
            $user_id = $conn->insert_id;
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            echo "<script>alert('Registration successful!'); window.location.href='profile.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
    
    $stmt->close();
}
?>
