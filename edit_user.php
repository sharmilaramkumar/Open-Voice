<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $result = $user_query->get_result();
    $user = $result->fetch_assoc();
    $user_query->close();
} else {
    header("Location: manage_users.php");
    exit();
}

// Update user details
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $roll_number = $_POST['roll_number'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $department = $_POST['department'];

    $update_query = $conn->prepare("UPDATE users SET name=?, username=?, age=?, dob=?, roll_number=?, address=?, phone_number=?, email=?, department=? WHERE id=?");
    $update_query->bind_param("ssissssssi", $name, $username, $age, $dob, $roll_number, $address, $phone_number, $email, $department, $user_id);
    
    if ($update_query->execute()) {
        header("Location: manage_users.php");
        exit();
    } else {
        $error_message = "Error: " . $update_query->error;
    }
    $update_query->close();
}

// Close the connection once, after all operations
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Open Voice</title>
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

        /* Container Styles */
        .container {
            max-width: 900px; /* Matches register.php */
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
        select {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background: #fafafa;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
        }

        input:focus,
        select:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
            outline: none;
            background: white;
        }

        input::placeholder {
            color: #999;
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
            grid-column: span 2;
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

            h1 {
                font-size: 2rem;
            }

            button {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
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
            select {
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
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Edit User</h1>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?php echo isset($user['age']) ? htmlspecialchars($user['age']) : ''; ?>" placeholder="Enter your age" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?php echo isset($user['dob']) ? htmlspecialchars($user['dob']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="roll_number">Roll Number</label>
                <input type="text" id="roll_number" name="roll_number" value="<?php echo isset($user['roll_number']) ? htmlspecialchars($user['roll_number']) : ''; ?>" placeholder="Enter your roll number">
            </div>
            <div class="form-group full-width">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" placeholder="Enter your address">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo isset($user['phone_number']) ? htmlspecialchars($user['phone_number']) : ''; ?>" placeholder="Enter your phone number">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" placeholder="Enter your email">
            </div>
            <div class="form-group full-width">
                <label for="department">Department</label>
                <select id="department" name="department">
                    <option value="" <?php echo !isset($user['department']) || empty($user['department']) ? 'selected' : ''; ?>>Select your department</option>
                    <option value="Computer Science" <?php echo isset($user['department']) && $user['department'] == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                    <option value="Computer Application" <?php echo isset($user['department']) && $user['department'] == 'Computer Application' ? 'selected' : ''; ?>>Computer Application</option>
                    <option value="Defence" <?php echo isset($user['department']) && $user['department'] == 'Defence' ? 'selected' : ''; ?>>Defence</option>
                    <option value="Maths" <?php echo isset($user['department']) && $user['department'] == 'Maths' ? 'selected' : ''; ?>>Maths</option>
                    <option value="English" <?php echo isset($user['department']) && $user['department'] == 'English' ? 'selected' : ''; ?>>English</option>
                    <option value="BBA" <?php echo isset($user['department']) && $user['department'] == 'BBA' ? 'selected' : ''; ?>>BBA</option>
                    <option value="B.Com" <?php echo isset($user['department']) && $user['department'] == 'B.Com' ? 'selected' : ''; ?>>B.Com</option>
                </select>
            </div>
            <button type="submit">Update User</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
