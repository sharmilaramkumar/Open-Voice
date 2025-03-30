<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
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
    <title>Register - Open Voice</title>
    <script>
        function toggleFields() {
            var role = document.getElementById("role").value;
            var userFields = document.getElementById("userFields");
            userFields.style.display = role === "user" ? "block" : "none";
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
            background: linear-gradient(135deg, #f5f6fa, #e0e7ff);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        /* Container Styles */
        .container {
            max-width: 900px; /* Increased from 700px */
            margin: 3rem auto;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 2.5rem;
            font-weight: 600;
        }

        /* Form Styles */
        form {
            display: grid;
            gap: 1.8rem;
            grid-template-columns: 1fr 1fr; /* Two-column layout */
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .full-width {
            grid-column: span 2; /* Full-width fields */
        }

        label {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
        }

        input,
        select,
        textarea {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background: #fafafa;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%; /* Ensure inputs take full available width */
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
            outline: none;
            background: white;
        }

        button {
            padding: 1.2rem;
            background: linear-gradient(135deg, #27ae60, #219653);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            grid-column: span 2; /* Button spans full width */
        }

        button:hover {
            background: linear-gradient(135deg, #219653, #1b8047);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(39, 174, 96, 0.3);
        }

        button:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
        }

        /* User Fields Section */
        #userFields {
            grid-column: span 2; /* Full width for user fields */
            padding-top: 1rem;
            border-top: 1px solid #eee;
            display: grid;
            gap: 1.8rem;
            grid-template-columns: 1fr 1fr;
        }

        /* Error Message */
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 6px;
            text-align: center;
            font-weight: 500;
            border: 1px solid #f5c6cb;
            margin-bottom: 1.5rem;
            grid-column: span 2;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
                margin: 1.5rem;
                padding: 2rem;
            }

            form {
                grid-template-columns: 1fr; /* Single column on smaller screens */
            }

            #userFields {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 2rem;
            }

            button {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            header {
                flex-direction: column;
                padding: 1rem;
                text-align: center;
            }

            header h1 {
                font-size: 1.5rem;
            }

            header img {
                height: 40px;
            }

            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            label {
                font-size: 1rem;
            }

            input,
            select,
            textarea {
                padding: 0.8rem;
            }

            button {
                font-size: 1.1rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <header>
        <h1>Open Voice</h1>
        <img src="logo.png" alt="header-logo">
    </header>

    <div class="container">
        <h1>Register</h1>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required onchange="toggleFields()">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group full-width">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number">
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div id="userFields" class="form-group">
                <div class="form-group">
                    <label for="roll_number">Roll Number</label>
                    <input type="text" id="roll_number" name="roll_number">
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department">
                        <option value="Computer Science">Computer Science</option>
                        <option value="Computer Application">Computer Application</option>
                        <option value="Maths">Maths</option>
                        <option value="English">English</option>
                        <option value="Defence">Defence</option>
                        <option value="BBA">BBA</option>
                        <option value="B.Com">B.Com</option>
                    </select>
                </div>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>

    <footer>
        <p>© <?php echo date('Y'); ?> Open Voice. All rights reserved.</p>
    </footer>
</body>
</html>
