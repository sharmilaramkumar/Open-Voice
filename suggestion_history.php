<?php
session_start();
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get suggestion history for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT created_at, category, description, status FROM suggestions WHERE user_id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestion History</title>
    <link rel="stylesheet" href="Styles.css">
    <style>
/* Reset default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    line-height: 1.6;
    background-color: #f5f6fa;
    color: #333;
    min-height: 100vh;
}

/* Header Styles */
.header {
    background: linear-gradient(135deg, #2c3e50, #3498db);
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header img {
    height: 50px;
    width: auto;
    margin-right: 1rem;
}

.header h1 {
    color: white;
    font-size: 1.8rem;
    font-weight: 600;
}

.header nav {
    display: flex;
    gap: 1.5rem;
}

.header nav a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.header nav a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.header nav a.active {
    background-color: #ffffff33;
    color: #fff;
}

.header nav a[href="logout.php"] {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.header nav a[href="logout.php"]:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
}

/* Container Styles */
.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

/* Heading Styles */
.container h1 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid #3498db;
    display: inline-block;
    padding-bottom: 0.5rem;
}

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
    font-weight: 500;
}

/* Footer Styles (Matching User Dashboard) */
footer {
    background-color: #2c3e50;
    color: white;
    text-align: center;
    padding: 1rem 2rem;
    margin-top: 2rem;
    font-size: 0.9rem;
    line-height: 1.6;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
}

footer p {
    margin: 0;
    font-weight: 400;
    transition: opacity 0.3s ease;
}

footer:hover p {
    opacity: 0.9;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        padding: 1rem;
    }

    .header nav {
        flex-direction: column;
        width: 100%;
        margin-top: 1rem;
        gap: 0.5rem;
    }

    .header nav a {
        width: 100%;
        text-align: center;
    }

    .container {
        margin: 1rem;
        padding: 1.5rem;
    }

    .container h1 {
        font-size: 1.8rem;
    }

    table {
        display: block;
        overflow-x: auto;
    }

    th, td {
        min-width: 150px;
    }

    footer {
        padding: 0.8rem 1.5rem;
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .header h1 {
        font-size: 1.5rem;
    }

    .header img {
        height: 40px;
    }

    .container {
        padding: 1rem;
    }

    .container h1 {
        font-size: 1.5rem;
    }

    footer {
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.container {
    animation: fadeIn 0.5s ease-out;
}

table {
    animation: fadeIn 0.7s ease-out;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Suggestion History</h1>
        <table>
            <tr>
                <th>Date & Time</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
