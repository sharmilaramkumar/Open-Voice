<?php
session_start();
include 'header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
}


/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

thead {
    background: #2c3e50;
    color: white;
}

th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

tbody tr {
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s ease;
}

tbody tr:hover {
    background-color: #f8f9fd;
}

td {
    padding: 1rem;
    vertical-align: middle;
}

td a {
    color: #3498db;
    text-decoration: none;
    transition: color 0.2s ease;
}

td a:hover {
    color: #2980b9;
    text-decoration: underline;
}

/* Action Links Styling */
td a[href*="edit_user"] {
    padding: 0.3rem 0.8rem;
    border-radius: 4px;
    background-color: #3498db1a;
    margin-right: 0.5rem;
}

td a[href*="edit_user"]:hover {
    background-color: #3498db33;
}

td a[href*="delete_user"] {
    padding: 0.3rem 0.8rem;
    border-radius: 4px;
    background-color: #c0392b1a;
    color: #c0392b;
}

td a[href*="delete_user"]:hover {
    background-color: #c0392b33;
    color: #c0392b;
}

/* Add Users Link */
a[href*="register.php"] {
    display: inline-block;
    margin: 1rem 2rem;
    padding: 0.8rem 1.5rem;
    background-color: #27ae60;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s ease;
    font-weight: 500;
}

a[href*="register.php"]:hover {
    background-color: #219653;
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        padding: 1rem;
    }

    nav {
        flex-direction: column;
        width: 100%;
        margin-top: 1rem;
        gap: 0.5rem;
    }

    nav a {
        width: 100%;
        text-align: center;
    }

    .container {
        padding: 0 1rem;
    }

    table {
        display: block;
        overflow-x: auto;
    }

    th, td {
        min-width: 120px;
    }

    a[href*="register.php"] {
        margin: 1rem;
        width: calc(100% - 2rem);
        text-align: center;
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

/* Footer Styles (Assuming footer.php exists) */
footer {
    padding: 1rem 2rem;
    background-color: #2c3e50;
    color: white;
    text-align: center;
    margin-top: 2rem;
}
        </style>
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
<td>No email available</td>


                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete <?php echo $row['username']; ?>?');">Delete</a>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="register.php<?php ?>">Add Users</a>
    
</body>
</html>

<?php
$conn->close();
?>
