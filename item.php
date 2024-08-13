<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: guest.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h2 {
            text-align: center;
            padding: 20px 0;
            color: #333;
        }

        form {
            width: 60%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: calc(100% - 20px);
            /* Adjusted width */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            display: block;
            /* Changed to block */
            margin: 0 auto;
            /* Centering the button */
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Navigation Bar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            display: inline-block;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
        }

        /* Footer Styles */
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            margin-top: auto;
        }

        /* Ensure content area grows to fit the viewport */
        .content {
            flex: 1;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            form {
                width: 80%;
            }

            input[type="text"],
            input[type="number"],
            input[type="file"],
            textarea {
                width: calc(100% - 16px);
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar a {
                padding: 10px 15px;
                width: 100%;
                text-align: left;
            }

            .navbar .logo {
                margin-bottom: 10px;
            }
        }

        #logout-link {
            cursor: pointer;
        }

        #logout-link:hover {
            color: rgb(157, 41, 41);
        }

        .confirmation-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            width: 300px;
            text-align: center;
        }

        .confirmation-box p {
            margin: 0 0 20px;
            font-size: 16px;
        }

        .confirmation-box button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        #confirm-logout {
            background-color: #28a745;
            color: #fff;
        }

        #confirm-logout:hover {
            background-color: #218838;
        }

        #cancel-logout {
            background-color: #dc3545;
            color: #fff;
        }

        #cancel-logout:hover {
            background-color: #c82333;
        }

        .confirmation-box button:focus {
            outline: none;
        }


        @media (max-width: 480px) {
            h2 {
                font-size: 18px;
                padding: 15px 0;
            }

            form {
                width: 90%;
                padding: 15px;
            }

            input[type="text"],
            input[type="number"],
            input[type="file"],
            textarea {
                padding: 6px;
                margin-bottom: 8px;
            }

            input[type="submit"] {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="logo" style="color: #fff;">Admin Dashboard</div>
        <div>
            <a href="/InRent/admin.php">User Management</a>
            <a href="/InRent/inventory.php">Inventory</a>
            <a href="/InRent/item.php">Add item</a>
            <a href="/InRent/order.php">Orders</a>
            <a class="log" id="logout-link">Log Out</a>

            <div id="confirmation-box" class="confirmation-box">
                <p>Are you sure you want to log out?</p>
                <button id="confirm-logout">Yes</button>
                <button id="cancel-logout">No</button>
            </div>
        </div>
    </div>

    <div class="content">
        <h2>Add New Item</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="model">Model:</label>
            <input type="text" id="model" name="model" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>

            <input type="submit" value="Submit">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection parameters
            $servername = "localhost"; // Change this if your MySQL server is hosted elsewhere
            $username = "root"; // Enter your MySQL username
            $password = ""; // Enter your MySQL password
            $database = "sign_up_info"; // Enter your MySQL database name
        
            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and bind the SQL statement
            $stmt = $conn->prepare("INSERT INTO items (name, model, price, quantity, image, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiss", $name, $model, $price, $quantity, $image, $description);

            // Set parameters and execute
            $name = ($_POST['name']);
            $model = $_POST['model'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $description = $_POST['description'];

            // Upload image
            $target_dir = "uploads/"; // Directory where uploaded images will be saved
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;

                // Check if data is inserted successfully
                if ($stmt->execute()) {
                    echo "<script>alert('Item added sucessfully.')</script>";
                    //header("Location: inventory.php");
                    exit();
                } else {
                    echo "<p class='error'>Error: " . $stmt->error . "</p>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2024 InRent. All rights reserved.
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const logoutLink = document.getElementById('logout-link');
            const confirmationBox = document.getElementById('confirmation-box');
            const confirmLogout = document.getElementById('confirm-logout');
            const cancelLogout = document.getElementById('cancel-logout');

            logoutLink.addEventListener('click', (event) => {
                event.preventDefault(); // Prevent default link behavior
                confirmationBox.style.display = 'block'; // Show the confirmation box
            });

            confirmLogout.addEventListener('click', () => {
                // Redirect to logout or handle logout logic here
                window.location.href = 'logout.php'; // Update with your logout URL
            });

            cancelLogout.addEventListener('click', () => {
                confirmationBox.style.display = 'none'; // Hide the confirmation box
            });
        });
    </script>
</body>

</html>