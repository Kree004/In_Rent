<?php
$host = 'localhost'; // Update with your host
$db = 'sign_up_info'; // Update with your database name
$user = 'root'; // Update with your username
$pass = ''; // Update with your password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'check_username') {
        $username = $conn->real_escape_string($_POST['username']);
        $result = $conn->query("SELECT COUNT(*) AS count FROM users WHERE username='$username'");
        $data = $result->fetch_assoc();
        echo json_encode(['exists' => $data['count'] > 0]);
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] == 'check_email') {
        $email = $conn->real_escape_string($_POST['email']);
        $result = $conn->query("SELECT COUNT(*) AS count FROM users WHERE email='$email'");
        $data = $result->fetch_assoc();
        echo json_encode(['exists' => $data['count'] > 0]);
        exit();
    }
}