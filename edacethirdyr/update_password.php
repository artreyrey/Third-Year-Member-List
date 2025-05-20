<?php
require_once 'connection.php';
session_start();

$username = 'admin123';  // Or get from session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
    $newPassword = $_POST['password']; // Store as plain text (not recommended)

    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $newPassword, $username);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Password cannot be empty.";
}

$conn->close();
?>
