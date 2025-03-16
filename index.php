<?php
session_start();

// Redirect to dashboard if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Open Voice</title>
    <link rel="stylesheet" href="styles.css">
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
    background: linear-gradient(135deg, #f5f6fa 0%, #e8eef6 100%);
    color: #333;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Landing Container Styles */
.landing-container {
    text-align: center;
    padding: 2rem;
    max-width: 600px;
    margin: 2rem auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.8s ease-out;
}

.landing-container.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Logo Styles */
.logo {
    max-width: 150px;
    height: auto;
    margin-bottom: 2rem;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.6s ease-out;
}

.logo.visible {
    opacity: 1;
    transform: scale(1);
}

/* Welcome Message Styles */
h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease-out;
}

h1.visible {
    opacity: 1;
    transform: translateY(0);
}

h3 {
    color: #3498db;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 500;
}

p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

/* Login Button Styles */
.login-button {
    padding: 0.8rem 2rem;
    font-size: 1.1rem;
    font-weight: 500;
    color: white;
    background: linear-gradient(135deg, #3498db, #2980b9);
    border: none;
    border-radius: 25px;
    cursor: pointer;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease-out, transform 0.3s ease, box-shadow 0.3s ease;
}

.login-button.visible {
    opacity: 1;
    transform: translateY(0);
}

.login-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    background: linear-gradient(135deg, #2980b9, #2471a3);
}

.login-button:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .landing-container {
        margin: 1rem;
        padding: 1.5rem;
    }

    h1 {
        font-size: 2rem;
    }

    h3 {
        font-size: 1.2rem;
    }

    p {
        font-size: 1rem;
    }

    .logo {
        max-width: 120px;
    }

    .login-button {
        padding: 0.7rem 1.5rem;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .landing-container {
        padding: 1rem;
    }

    h1 {
        font-size: 1.8rem;
    }

    h3 {
        font-size: 1.1rem;
    }

    .logo {
        max-width: 100px;
    }
}

/* Enhanced Animation for Initial Load */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ensure smooth transitions align with JS timing */
.landing-container, .logo, h1, .login-button {
    will-change: opacity, transform;
}
    </style>
</head>
<body>
    <div class="landing-container" id="landingContainer">
        <!-- Company Logo -->
        <img src="logo.png" alt="Company Logo" class="logo" id="logo">
        
        <!-- Welcome Message -->
        <h1 id="welcomeMessage">Welcome to Open Voice</h1>
<h3>DBCY's Virtual Suggestion Box</h3>
<p>Your feedback matters. Submit suggestions and help us improve!</p>
        <!-- Login Button -->
        <button class="login-button" id="loginButton" onclick="window.location.href='login.php'">Login</button>
    </div>

    <script>
        // JavaScript to trigger animations
        document.addEventListener("DOMContentLoaded", function () {
            const landingContainer = document.getElementById("landingContainer");
            const logo = document.getElementById("logo");
            const welcomeMessage = document.getElementById("welcomeMessage");
            const loginButton = document.getElementById("loginButton");

            // Add visible class to elements after a short delay
            setTimeout(() => {
                landingContainer.classList.add("visible");
            }, 100);

            setTimeout(() => {
                logo.classList.add("visible");
            }, 500);

            setTimeout(() => {
                welcomeMessage.classList.add("visible");
            }, 1000);

            setTimeout(() => {
                loginButton.classList.add("visible");
            }, 1500);
        });
    </script>
</body>
</html>