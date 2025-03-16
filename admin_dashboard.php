<?php
// Start the session
session_start();
include 'header.php';

// Redirect to login if user is not authenticated or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Regenerate session ID to prevent session fixation
session_regenerate_id(true);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all suggestions
$result = $conn->query("SELECT * FROM suggestions");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

tr {
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s ease;
}

tr:hover {
    background-color: #f8f9fd;
}

th {
    background: #2c3e50;
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

td {
    padding: 1rem;
    vertical-align: middle;
}

/* Status Styling */
td:where(:contains('Pending')) {
    color: #f39c12;
    font-weight: 500;
}

td:where(:contains('Approved')) {
    color: #27ae60;
    font-weight: 500;
}

td:where(:contains('Rejected')) {
    color: #c0392b;
 }
    </style>
</head>
<body>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <?php if ($row['file_path']): ?>
                                <a href="<?php echo $row['file_path']; ?>" target="_blank">View File</a>
                            <?php else: ?>
                                No file
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Approved">Approve</a> |
                            <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Rejected">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>