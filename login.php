<?php
session_start();

// Validate login credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';


    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'suggestion_box');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Set session variables
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    display: flex;
    flex-direction: column;
}

/* Header Styles */
header {
    background: linear-gradient(135deg, #2c3e50, #3498db);
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

header img {
    height: 50px;
    width: auto;
    margin-right: 1rem;
}

header h1 {
    color: white;
    font-size: 1.8rem;
    font-weight: 600;
}

/* Container Styles */
.container {
    max-width: 400px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

/* Form Heading Styles */
.container h2 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    text-align: center;
    border-bottom: 2px solid #3498db;
    display: inline-block;
    padding-bottom: 0.5rem;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

label {
    color: #2c3e50;
    font-weight: 500;
    margin-bottom: 0.3rem;
}

input[type="text"],
input[type="password"] {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    outline: none;
}

input[type="text"]::placeholder,
input[type="password"]::placeholder {
    color: #999;
}

button[type="submit"] {
    padding: 0.8rem;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

button[type="submit"]:hover {
    background: linear-gradient(135deg, #2980b9, #2471a3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
}

button[type="submit"]:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

/* Footer Styles (Enhancing inline styles) */
footer {
    background: linear-gradient(135deg, #ff7e5f, rgb(193, 39, 232));
    color: #fff;
    text-align: center;
    padding: 1rem;
    margin-top: auto;
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    header {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
    }

    header img {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .container {
        margin: 1rem;
        padding: 1.5rem;
    }

    .container h2 {
        font-size: 1.5rem;
    }

    input[type="text"],
    input[type="password"],
    button[type="submit"] {
        padding: 0.7rem;
    }
}

@media (max-width: 480px) {
    header h1 {
        font-size: 1.5rem;
    }

    header img {
        height: 40px;
    }

    .container {
        padding: 1rem;
    }

    .container h2 {
        font-size: 1.3rem;
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

form {
    animation: fadeIn 0.7s ease-out;
}


</style>
</head>
<body>
    <header>
    <img src="logo.png" alt="header-logo">
        <h1>
            Open Voice
        </h1>
        </header>

    <div class="container">

        <h2>Login</h2>
        <form method="POST" action="login.php">
            <label for="text">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>


            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit">LOGIN</button>
        </form>
    </div>
    
</body>
</html>
