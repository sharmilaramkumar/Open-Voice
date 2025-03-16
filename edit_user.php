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
    $user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $user = $user_query->fetch_assoc();
} else {
    header("Location: manage_users.php");
    exit();
}

// Update user details
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
        echo "Error: " . $update_query->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Edit User</h1>
        <form method="POST">
            <input type="text" id="name" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" placeholder="Enter your name" required>
            <input type="text" id="username" name="username" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>" placeholder="Enter your username" required>
            <input type="number" id="age" name="age" value="<?php echo isset($user['age']) ? $user['age'] : ''; ?>" placeholder="Enter your age" required>
            <input type="date" id="dob" name="dob" value="<?php echo isset($user['dob']) ? $user['dob'] : ''; ?>" placeholder="Select your date of birth" required>
            <input type="text" id="roll_number" name="roll_number" value="<?php echo isset($user['roll_number']) ? $user['roll_number'] : ''; ?>" placeholder="Enter your roll number">
            <input type="text" id="address" name="address" value="<?php echo isset($user['address']) ? $user['address'] : ''; ?>" placeholder="Enter your address">
            <input type="text" id="phone_number" name="phone_number" value="<?php echo isset($user['phone_number']) ? $user['phone_number'] : ''; ?>" placeholder="Enter your phone number">
            <input type="email" id="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" placeholder="Enter your email">
            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="" disabled>Select your department</option>
                <option value="HR" <?php echo (isset($user['department']) && $user['department'] == 'HR') ? 'selected' : ''; ?>>Computer Science</option>
                <option value="IT" <?php echo (isset($user['department']) && $user['department'] == 'IT') ? 'selected' : ''; ?>>Computer Application</option>
                <option value="Finance" <?php echo (isset($user['department']) && $user['department'] == 'Finance') ? 'selected' : ''; ?>>Diffence</option>
                <option value="Marketing" <?php echo (isset($user['department']) && $user['department'] == 'Marketing') ? 'selected' : ''; ?>>Maths</option>
                <option value="Sales" <?php echo (isset($user['department']) && $user['department'] == 'Sales') ? 'selected' : ''; ?>>English</option>
                <option value="Sales" <?php echo (isset($user['department']) && $user['department'] == 'Sales') ? 'selected' : ''; ?>>BBA</option>
                <option value="Sales" <?php echo (isset($user['department']) && $user['department'] == 'Sales') ? 'selected' : ''; ?>>B.Com</option>
            </select>
            <button type="submit">Update User</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
