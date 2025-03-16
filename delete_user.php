<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $delete_query = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_query->bind_param("i", $user_id);
    
    if ($delete_query->execute()) {
        header("Location: manage_users.php?message=User deleted successfully");
        exit();
    } else {
        echo "Error: " . $delete_query->error;
    }
} else {
    header("Location: manage_users.php");
    exit();
}

$conn->close();
?>
