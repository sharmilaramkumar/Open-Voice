<?php
include 'header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all suggestions
$suggestions = $conn->query("SELECT * FROM suggestions ORDER BY created_at DESC");
?>

<div class="container">
    <h1>Manage Suggestions</h1>
    <?php if (isset($_GET['message'])): ?>
        <p class="message <?php echo strpos($_GET['message'], 'Error') === false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars(urldecode($_GET['message'])); ?>
        </p>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Description</th>
                <th>File</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $suggestions->fetch_assoc()): ?>
                <tr id="suggestion-<?php echo $row['id']; ?>">
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <?php if ($row['file_path']): ?>
                            <a href="<?php echo $row['file_path']; ?>" download>Download File</a>
                        <?php else: ?>
                            None
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['status'] ?? 'Pending'; ?></td>
                    <td>
                        <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Approved">Approve</a> |
                        <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Rejected">Reject</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<style>
    /* Page-specific styles */
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        line-height: 1.3;
        background: url('backgroundOV.jpeg') no-repeat center center fixed; 
        background-size: 100%;
        color: #333;
        min-height: 80vh;
    }

    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background: #f8f9fd;
        color: #2c3e50;
    }

    tr:target {
        background: #f8f9fd;
        transition: background 0.5s ease;
    }

    /* Style the download link */
    a[download] {
        color: #27ae60; /* Green to indicate download */
        text-decoration: none;
    }

    a[download]:hover {
        text-decoration: underline;
        color: #219653;
    }

    /* Message styles for update_status.php feedback */
    .message {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 5px;
        text-align: center;
        font-weight: 500;
    }

    .message.success {
        background-color: #dff0d8;
        color: #3c763d;
        border: 1px solid #d6e9c6;
    }

    .message.error {
        background-color: #f2dede;
        color: #a94442;
        border: 1px solid #ebccd1;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .container {
        animation: fadeIn 0.5s ease-out;
    }
</style>

<?php
$conn->close();
include 'footer.php';
?>
