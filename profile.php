<?php
session_start();
include 'header.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'suggestion_box');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, age, dob, roll_number, address, phone_number, department FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name, $age, $dob, $roll_number, $address, $phone_number, $department);

if ($stmt->fetch()) {
    // Store user details in the session (optional)
    $_SESSION['user'] = [
        'name' => $name,
        'age' => $age,
        'dob' => $dob,
        'roll_number' => $roll_number,
        'address' => $address,
        'phone_number' => $phone_number,
        'department' => $department,
    ];
} else {
    // Handle case where user details are not found
    $_SESSION['user'] = [];
}

$stmt->close();
$conn->close();
?>

<div class="pro-container">
    <h1>Profile</h1>
    <div class="profile-details">
        <h2>Details</h2>
        <div class="detail-item">
            <span class="detail-label">Name:</span>
            <span class="detail-value"><?php echo isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'N/A'; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Age:</span>
            <span class="detail-value"><?php echo isset($_SESSION['user']['age']) ? $_SESSION['user']['age'] : 'N/A'; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Date of Birth:</span>
            <span class="detail-value"><?php echo isset($_SESSION['user']['dob']) ? $_SESSION['user']['dob'] : 'N/A'; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Roll Number:</span>
            <span class="detail-value"><?php echo isset($_SESSION['user']['roll_number']) ? $_SESSION['user']['roll_number'] : 'N/A'; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Address:</span>
            <span class="detail-value"><?php echo isset($_SESSION['user']['address']) ? $_SESSION['user']['address'] : 'N/A'; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Phone Number:</span>
            <span class="detail-value"><?php echo isset($_SESSION['user']['phone_number']) ? $_SESSION['user']['phone_number'] : 'N/A'; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Department:</span>
            <span class="detail-value"><?php echo isset($_SESSION['user']['department']) ? $_SESSION['user']['department'] : 'N/A'; ?></span>
        </div>
    </div>
</div>

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

    /* Profile Container Styles */
    .pro-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .pro-container h1 {
        color: #2c3e50;
        margin-bottom: 2rem;
        font-size: 2rem;
        border-bottom: 2px solid #3498db;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    /* Profile Details Styles */
    .profile-details {
        padding: 1.5rem;
        background: #f8f9fd;
        border-radius: 6px;
    }

    .profile-details h2 {
        color: #2c3e50;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .profile-details h2::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 50px;
        height: 2px;
        background: #3498db;
    }

    .detail-item {
        display: flex;
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s ease;
    }

    .detail-item:hover {
        background-color: #f0f2f5;
    }

    .detail-label {
        flex: 0 0 180px;
        font-weight: 600;
        color: #2c3e50;
    }

    .detail-value {
        flex: 1;
        color: #555;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            padding: 1rem;
        }

        nav {
            flex-direction: column;
            width: 100%;
            margin-top: 1rem;
            gap: 0.5rem;
        }

        nav a {
            width: 100%;
            text-align: center;
        }

        .pro-container {
            margin: 1rem;
            padding: 1rem;
        }

        .detail-item {
            flex-direction: column;
            padding: 1rem 0;
        }

        .detail-label {
            flex: none;
            margin-bottom: 0.5rem;
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

    .pro-container {
        animation: fadeIn 0.5s ease-out;
    }

    /* Special Cases */
    .detail-value:empty::before {
        content: 'Not Available';
        color: #999;
        font-style: italic;
    }
</style>

<?php include 'footer.php'; ?>
