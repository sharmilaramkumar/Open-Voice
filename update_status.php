<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $stmt = $conn->prepare("UPDATE suggestions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");

    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<?php include 'header.php'; ?>

<?php include 'footer.php'; ?>
