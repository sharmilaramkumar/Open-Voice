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

// Fetch user's suggestion stats
$user_id = $_SESSION['user_id'];
$total_submitted = $conn->query("SELECT COUNT(*) FROM suggestions WHERE user_id = $user_id")->fetch_row()[0];
$total_approved = $conn->query("SELECT COUNT(*) FROM suggestions WHERE user_id = $user_id AND status = 'Approved'")->fetch_row()[0];
$total_rejected = $conn->query("SELECT COUNT(*) FROM suggestions WHERE user_id = $user_id AND status = 'Rejected'")->fetch_row()[0];

// Fetch user's suggestions
$stmt = $conn->prepare("SELECT id, category, description, file_path, status, created_at 
                        FROM suggestions 
                        WHERE user_id = ? 
                        ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$suggestions = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<div class="container">
    <h1>User Dashboard</h1>

    <!-- Stats Summary -->
    <div class="stats-summary">
        <div>
            <h3>Total Submitted</h3>
            <p class="total"><?php echo $total_submitted; ?></p>
        </div>
        <div>
            <h3>Total Approved</h3>
            <p class="approved"><?php echo $total_approved; ?></p>
        </div>
        <div>
            <h3>Total Rejected</h3>
            <p class="rejected"><?php echo $total_rejected; ?></p>
        </div>
    </div>

    <!-- Suggestion History -->
    <section class="suggestion-history">
        <h2>Your Suggestions</h2>
        <?php if ($suggestions->num_rows > 0): ?>
            
        <?php else: ?>
            <p>You haven’t submitted any suggestions yet. <a href="submit_suggestion.php">Submit one now!</a></p>
        <?php endif; ?>
    </section>

    <!-- Women's Cell Coordinator -->
    <div class="womens-cell">
        <h2>Women's Cell Coordinator</h2>
        <img src="bca-0.jpg" alt="Women's Cell Coordinator">
        <p><strong>Name:</strong> Dr. Jayamary</p>
        <p><strong>Contact:</strong> <a href="tel:+1234567890">+1 (234) 567-890</a></p>
        <p><strong>Email:</strong> <a href="mailto:jane.doe@example.com">jane.doe@example.com</a></p>
    </div>
</div>

<style>
    /* Existing styles remain unchanged */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        line-height: 1.3;
        background: url('backgroundOV.jpeg') no-repeat center center fixed; 
        background-size: 100%;
        color: #333;
        min-height: 80vh;
    }

    .container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .container h1 {
        color: #2c3e50;
        font-size: 2rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #3498db;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    .stats-summary {
        display: flex;
        justify-content: space-around;
        margin-bottom: 2rem;
    }

    .stats-summary div {
        text-align: center;
        padding: 1rem;
        background: #f8f9fd;
        border-radius: 5px;
        width: 30%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-summary div:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-summary h3 {
        color: #2c3e50;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .stats-summary p {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .stats-summary .total { color: #3498db; }
    .stats-summary .approved { color: #27ae60; }
    .stats-summary .rejected { color: #c0392b; }

    /* Suggestion History Styles */
    .suggestion-history {
        margin-bottom: 2rem;
    }

    .suggestion-history h2 {
        color: #2c3e50;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #3498db;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    .suggestion-history table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }

    .suggestion-history th, .suggestion-history td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .suggestion-history th {
        background: #f8f9fd;
        color: #2c3e50;
    }

    .suggestion-history tr:target {
        background: #f8f9fd;
        transition: background 0.5s ease;
    }

    .suggestion-history a {
        color: #3498db;
        text-decoration: none;
    }

    .suggestion-history a:hover {
        text-decoration: underline;
    }

    .suggestion-history p {
        text-align: center;
        color: #555;
    }

    /* Women's Cell Coordinator Styles */
    .womens-cell {
        background: #f8f9fd;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .womens-cell h2 {
        color: #2c3e50;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #3498db;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    .womens-cell img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin-bottom: 1rem;
        object-fit: cover;
        border: 3px solid #3498db;
    }

    .womens-cell p {
        color: #555;
        font-size: 1.1rem;
        margin: 0.5rem 0;
    }

    .womens-cell a {
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
    }

    .womens-cell a:hover {
        text-decoration: underline;
        color: #2980b9;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            margin: 1rem;
            padding: 1.5rem;
        }

        .container h1 {
            font-size: 1.8rem;
        }

        .stats-summary {
            flex-direction: column;
            gap: 1rem;
        }

        .stats-summary div {
            width: 100%;
        }

        .suggestion-history h2 {
            font-size: 1.3rem;
        }

        .suggestion-history th, .suggestion-history td {
            padding: 0.8rem;
        }

        .womens-cell img {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 1rem;
        }

        .container h1 {
            font-size: 1.5rem;
        }

        .stats-summary p {
            font-size: 1.2rem;
        }

        .suggestion-history h2 {
            font-size: 1.2rem;
        }

        .womens-cell h2 {
            font-size: 1.3rem;
        }

        .womens-cell p {
            font-size: 1rem;
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

    .stats-summary {
        animation: fadeIn 0.7s ease-out;
    }

    .suggestion-history {
        animation: fadeIn 0.9s ease-out;
    }

    .womens-cell {
        animation: fadeIn 1.1s ease-out;
    }
</style>

<?php include 'footer.php'; ?>
