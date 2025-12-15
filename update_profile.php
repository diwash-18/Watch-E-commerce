<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$address = $_POST['address'];

$sql = "UPDATE users SET name=?, email=?, gender=?, dob=?, address=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $name, $email, $gender, $dob, $address, $id);

if ($stmt->execute()) {
    echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
} else {
    echo "<script>alert('Failed to update profile!'); window.location.href='profile.php';</script>";
}
?>
