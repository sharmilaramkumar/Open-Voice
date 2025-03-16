<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Voice</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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
.header nav a[href="logout.php"] {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

nav {
    display: flex;
    gap: 1.5rem;
}

nav a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s ease;
}

nav a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

nav a.active {
    background-color: #ffffff33;
    color: #fff;
}

/* Container Styles */
.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.container h1 {
    color: #2c3e50;
    margin-bottom: 2rem;
    font-size: 2rem;
    border-bottom: 2px solid #3498db;
    display: inline-block;
    padding-bottom: 0.5rem;
}

    </style>
</head>
<body>
    <header class="header">
    <img src="logo.png" alt="header-logo">
        <h1>Open Voice</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
<a href="admin_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active' : ''; ?>">🏠 Dashboard</a>

<a href="manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'manage_users.php' ? 'active' : ''; ?>">👥 Manage Users</a>

                <?php else: ?>
                    <a href="user_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'user_dashboard.php' ? 'active' : ''; ?>">🏠 Dashboard</a>
                    <a href="submit_suggestion.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'submit_suggestion.php' ? 'active' : ''; ?>">📝 Submit Suggestion</a>
                <?php endif; ?>

                <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>">👤 Profile</a>
<a href="logout.php">🚪 Logout</a>


            <?php else: ?>
                <a href="login.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'active' : ''; ?>">Login</a>

            <?php endif; ?>
        </nav>
    </header>
    <main>
