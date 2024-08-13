<?php
session_start();

// Database connection parameters
$host = 'localhost';
$db = 'sign_up_info';
$db_username = 'root';
$db_password = '';

// Special credentials
$special_username = 'admin';
$special_password = '1';
$special_password_hash = password_hash($special_password, PASSWORD_DEFAULT); // Make sure to hash the special password

// Create connection
$conn = new mysqli($host, $db_username, $db_password, $db);

// Check connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for special credentials
    if ($username === $special_username && password_verify($password, $special_password_hash)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php ");
        exit;
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($db_username, $db_password_hash);

    // Fetch the result
    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($password, $db_password_hash)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $db_username;
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid username or password. Password does not match.";
        }
    } else {
        echo "Invalid username or password. Username not found.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
