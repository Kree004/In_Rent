<?php
session_start();

$sessionUsername = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receive data from AJAX request
    $data = json_decode(file_get_contents('php://input'), true);

    // Process the received data
    $name = $data['name'];
    $days = $data['days'];
    $model = $data['model'];
    $price = $data['totalPrice'];

    // Perform database operation
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $database = "sign_up_info";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use a prepared statement to securely fetch the user data
    $sql = $conn->prepare("SELECT id, username FROM users WHERE username = ?");
    $sql->bind_param("s", $sessionUsername);

    // Execute the prepared statement
    $sql->execute();

    // Get the result
    $result = $sql->get_result();

    // Initialize variables
    $userId = null;
    $dbUsername = null;

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the row data
        $row = $result->fetch_assoc();
        $userId = $row["id"];
        $dbUsername = $row["username"];
    } else {
        echo "No results found.";
        $sql->close();
        $conn->close();
        exit(); // Stop further execution if no user is found
    }

    $sql->close();

    // Perform SQL injection prevention by using prepared statements
    $stmt = $conn->prepare("INSERT INTO rentals (userid, username, name, model, days, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssii", $userId, $dbUsername, $name, $model, $days, $price);

    if ($stmt->execute()) {
        // Decrement the quantity of the item by 1
        $updateStmt = $conn->prepare("UPDATE items SET quantity = quantity - 1 WHERE name = ? AND model = ?");
        $updateStmt->bind_param("ss", $name, $model);
        
        if ($updateStmt->execute()) {
            echo "Item rented successfully and quantity updated.";
        } else {
            echo "Error updating item quantity: " . $updateStmt->error;
        }

        $updateStmt->close();
        $stmt->close();
        $conn->close();
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit(); // Stop further execution
}