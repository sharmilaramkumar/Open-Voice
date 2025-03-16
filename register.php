<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        if ($role === 'user') {
            $roll_number = $_POST['roll_number'];
            $department = $_POST['department'];
            $stmt = $conn->prepare("INSERT INTO users (name, username, password, email, address, phone_number, age, dob, roll_number, department, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssissss", $name, $username, $hashed_password, $email, $address, $phone_number, $age, $dob, $roll_number, $department, $role);
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, username, password, email, address, phone_number, age, dob, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssiss", $name, $username, $hashed_password, $email, $address, $phone_number, $age, $dob, $role);
        }
        
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['role'] = $role;
            
            if ($role === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script>
        function toggleFields() {
            var role = document.getElementById("role").value;
            var userFields = document.getElementById("userFields");
            
            if (role === "user") {
                userFields.style.display = "block";
            } else {
                userFields.style.display = "none";
            }
        }
    </script>
    <style>
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
    justify-content: space-between;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

header img {
    height: 50px;
    width: auto;
}

header h1 {
    color: white;
    font-size: 1.8rem;
    font-weight: 600;
}

/* Container for Form */
.container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

/* Page Heading */
h1 {
    text-align: center;
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #3498db;
    display: inline-block;
    padding-bottom: 0.5rem;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}

label {
    color: #2c3e50;
    font-weight: 500;
}

input,
select {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input:focus,
select:focus {
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    outline: none;
}

button {
    padding: 0.8rem;
    background: linear-gradient(135deg, #27ae60, #219653);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover {
    background: linear-gradient(135deg, #219653, #1b8047);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
}

button:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
}

/* Error Message */
p[style*='color:red;'] {
    background: #f2dede;
    color: #a94442;
    padding: 0.8rem;
    border: 1px solid #ebccd1;
    border-radius: 5px;
    text-align: center;
}

/* Footer */
footer {
    color: white;
    text-align: center;
    padding: 1rem;
    margin-top: auto;
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    header {
        flex-direction: column;
        text-align: center;
    }

    .container {
        margin: 1rem;
        padding: 1.5rem;
    }

    h1 {
        font-size: 1.8rem;
    }

    input,
    select,
    button {
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

    h1 {
        font-size: 1.5rem;
    }
}

</style>
</head>
<body>
<header>
        <h1>
            Open Voice
        </h1>
        <img src="logo.png" alt="header-logo">
    </header>

    <h1>Register</h1>
    <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
<div class="container">
    <form action="register.php" method="POST">
        <label for="role">Role:</label>
        <select id="role" name="role" required onchange="toggleFields()">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address">

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number">

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>

        <div id="userFields" style="display:block;">
            <label for="roll_number">Roll Number:</label>
            <input type="text" id="roll_number" name="roll_number">
<br>
            <label for="department">Department:</label>
            <select id="department" name="department">
                <option value="cs">Computer Science</option>
                <option value="ca">Computer Application</option>
                <option value="ma">Maths</option>
                <option value="eng">English</option>
                <option value="diff">Defence</option>
                <option value="bba">BBA</option>
                <option value="bcom">B.Com</option>
            </select>
        </div>

        <button type="submit">Register</button>
    </form>
    </div>
    <footer style="background:linear-gradient(135deg, #2c3e50, #3498db);     color: #fff; text-align: center; padding: 1rem; margin-top: auto; box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);">
        <p>&copy; <?php echo date('Y'); ?> Open Voice. All rights reserved.</p>
    </footer>
</body>
</html>
