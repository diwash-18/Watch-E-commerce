<?php
session_start();
include 'db.php'; // your database connection file

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $userId = $_SESSION['user_id'];

  if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
      mkdir($targetDir);
    }

    $fileName = basename($_FILES["profile_pic"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
      $sql = "UPDATE users SET profile_pic = '$targetFile' WHERE id = $userId";
      mysqli_query($conn, $sql);
      header('Location: profile.php?msg=picture_updated');
      exit();
    } else {
      echo "Failed to upload file.";
    }
  }
}
?>
