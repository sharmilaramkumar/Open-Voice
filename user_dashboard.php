<?php
session_start();
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
<style>
/* Reset default styles */
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
    width: 50px;
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
    max-width: 1000px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

/* Dashboard Heading Styles */
.container h1 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid #3498db;
    display: inline-block;
    padding-bottom: 0.5rem;
}

/* Dashboard Navigation Styles */
.container nav {
    margin-bottom: 2rem;
}

.container nav ul {
    list-style: none;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.container nav li {
    flex: 1;
    min-width: 200px;
}

.container nav a {
    display: block;
    padding: 0.8rem 1.5rem;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(135deg, #3498db, #2980b9);
    border-radius: 6px;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s ease;
}

.container nav a:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    background: linear-gradient(135deg, #2980b9, #2471a3);
}

.container nav a:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

.container nav a[href="logout.php"] {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.container nav a[href="logout.php"]:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
}

/* Footer Styles (Assuming footer.php exists) */
footer {
    color: white;
    text-align: center;
    padding: 1rem;
    margin-top: auto;
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
    bottom: 100;
    position}



/*}

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

    .container nav ul {
        flex-direction: column;
        gap: 0.8rem;
    }

    .container nav li {
        min-width: 100%;
    }

    .container nav a {
        padding: 0.7rem 1rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 1rem;
    }

    .container h1 {
        font-size: 1.5rem;
    }

    .header h1 {
        font-size: 1.5rem;
    }

    .header img {
        height: 40px;
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

.container nav ul {
    animation: fadeIn 0.7s ease-out;
}
    </style>
</head>
<body>


    <div class="container">
        <h1>User Dashboard</h1>
       
    </div>
    <footer style="background:linear-gradient(135deg, #2c3e50, #3498db);     color: #fff; text-align: center; padding: 1rem; margin-top: auto; box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);">
        <p>&copy; <?php echo date('Y'); ?> Open Voice. All rights reserved.</p>
    </footer>
</body>
</html>
