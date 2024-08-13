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
    <title>Admin Page</title>
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

        h1 {
            text-align: center;
            padding: 20px 0;
            color: #333;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .delete-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-btn:hover {
            background-color: #c82333;
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            table {
                width: 100%;
            }

            th,
            td {
                padding: 8px 10px;
                font-size: 14px;
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

        @media (max-width: 480px) {

            th,
            td {
                font-size: 12px;
            }

            .delete-btn {
                padding: 6px 10px;
                font-size: 12px;
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
        <h1>User Management - Admin Page</h1>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php
            // PHP code to retrieve and display user data
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "sign_up_info";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve user data
            $sql = "SELECT id, username, email FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <button class='delete-btn' type='submit' name='delete'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No results found</td></tr>";
            }

            // Handle delete request
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
                $userId = $_POST["id"];
                $deleteSql = "DELETE FROM users WHERE id = ?";
                $stmt = $conn->prepare($deleteSql);
                $stmt->bind_param("i", $userId);

                if ($stmt->execute()) {
                    echo "<script>alert('User deleted successfully');</script>";
                    echo "<script>window.location.href = 'admin.php';</script>";
                } else {
                    echo "<script>alert('Error deleting user: " . $conn->error . "');</script>";
                }

                $stmt->close();

            }
            $conn->close();
            ?>
        </table>
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

        // window.addEventListener('beforeunload', function () {
        //     navigator.sendBeacon('logout.php');
        // });
    </script>
</body>

</html>