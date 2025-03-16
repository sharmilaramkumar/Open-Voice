    <?php
    include 'header.php';
    $conn = new mysqli('localhost', 'root', '', 'suggestion_box');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize $message with default values
    $message = ['type' => '', 'text' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category = $_POST['category'];
        $description = $_POST['description'];
        $file_path = null;
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL; // Anonymous if not logged in

        // Handle file upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_name = basename($_FILES['file']['name']);
            $file_path = $upload_dir . $file_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
        }

        // Insert suggestion into database
        $stmt = $conn->prepare("INSERT INTO suggestions (user_id, category, description, file_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $category, $description, $file_path);
        if ($stmt->execute()) {
            $message = ['type' => 'success', 'text' => 'Suggestion submitted successfully!'];
        } else {
            $message = ['type' => 'error', 'text' => 'Error: ' . $stmt->error];
        }
        $stmt->close();
    }

    $conn->close();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Submit Suggestion</title>
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
    width: auto;
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

/* Suggestion Container Styles */
.sugg_container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

/* Heading Styles */
.sugg_container h1 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid #3498db;
    display: inline-block;
    padding-bottom: 0.5rem;
    text-align: center;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

label {
    color: #2c3e50;
    font-weight: 500;
    margin-bottom: 0.3rem;
}

select,
textarea,
input[type="file"] {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    font-family: 'Segoe UI', Arial, sans-serif;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

select:focus,
textarea:focus,
input[type="file"]:focus {
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    outline: none;
}

textarea {
    resize: vertical;
    min-height: 120px;
}

textarea::placeholder {
    color: #999;
}

button[type="submit"] {
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

button[type="submit"]:hover {
    background: linear-gradient(135deg, #219653, #1b8047);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
}

button[type="submit"]:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
}

/* Message Styles */
.message {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: 5px;
    text-align: center;
    font-weight: 500;
}

.message.success {
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
}

.message.error {
    background-color: #f2dede;
    color: #a94442;
    border: 1px solid #ebccd1;
}

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

    .sugg_container {
        margin: 1rem;
        padding: 1.5rem;
    }

    .sugg_container h1 {
        font-size: 1.8rem;
    }

    select,
    textarea,
    input[type="file"],
    button[type="submit"] {
        padding: 0.7rem;
    }
}

@media (max-width: 480px) {
    .header h1 {
        font-size: 1.5rem;
    }

    .header img {
        height: 40px;
    }

    .sugg_container {
        padding: 1rem;
    }

    .sugg_container h1 {
        font-size: 1.5rem;
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

.sugg_container {
    animation: fadeIn 0.5s ease-out;
}

form {
    animation: fadeIn 0.7s ease-out;
}  
</style>
    </head>
    <body>
  
        <div class="sugg_container">
            <h1>Open Your Voice for a Change</h1>
            <form action="submit_suggestion.php" method="POST" enctype="multipart/form-data">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="General">General</option>
                    <option value="Suggestion">Suggestion</option>
                    <option value="Complaint">Complaint</option>
                </select>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" placeholder="Enter your suggestion" required></textarea>

                <label for="file">Upload File (optional):</label>
                <input type="file" id="file" name="file">

                <button type="submit">Submit Suggestion</button>
            </form>
            <?php if (!empty($message['text'])): ?>
                <div class="message <?php echo $message['type']; ?>"><?php echo $message['text']; ?></div>
            <?php endif; ?>
        </div>
    </body>
    </html>
