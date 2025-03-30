<?php
session_start();

// Database connection and logic before any output
$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check admin access and process status update
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $suggestion_id = (int)$_GET['id'];
    $status = $_GET['status'] === 'Approved' ? 'Approved' : 'Rejected';

    // Update suggestion status
    $stmt = $conn->prepare("UPDATE suggestions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $suggestion_id);
    if ($stmt->execute()) {
        // Get the user_id of the suggestion submitter
        $user_stmt = $conn->prepare("SELECT user_id FROM suggestions WHERE id = ?");
        $user_stmt->bind_param("i", $suggestion_id);
        $user_stmt->execute();
        $result = $user_stmt->get_result();
        $suggestion = $result->fetch_assoc();
        $user_id = $suggestion['user_id'];
        $user_stmt->close();

        if ($user_id) {
            // Notify the user
            $message = "Your suggestion (ID: $suggestion_id) has been $status.";
            $notify_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_read, suggestion_id) VALUES (?, ?, '0', ?)");
            $notify_stmt->bind_param("isi", $user_id, $message, $suggestion_id);
            $notify_stmt->execute();
            $notify_stmt->close();
        }

        $redirect_message = urlencode("Status updated successfully");
        header("Location: admin_dashboard.php?message=$redirect_message");
    } else {
        $redirect_message = urlencode("Error updating status: " . $stmt->error);
        header("Location: admin_dashboard.php?message=$redirect_message");
    }
    $stmt->close();
} else {
    $redirect_message = urlencode("Invalid request");
    header("Location: admin_dashboard.php?message=$redirect_message");
}

$conn->close();
exit();

// Include header.php only if needed (e.g., for an error page), but not here since we're redirecting
// include 'header.php'; // Moved after logic, but unnecessary with redirects
?>
