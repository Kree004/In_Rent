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
    <title>Order Management - Admin Page</title>
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
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow-x: auto;
        }

        th, td {
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            table {
                width: 100%;
            }

            th, td {
                padding: 10px;
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
            h1 {
                font-size: 18px;
                padding: 15px 0;
            }

            th, td {
                padding: 8px;
                font-size: 12px;
            }

            .delete-btn {
                padding: 6px 10px;
                font-size: 12px;
            }

            table {
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

    <h1>Order Management - Admin Page</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Order_ID</th>
            <th>Name</th>
            <th>Model</th>
            <th>Days</th>
            <th>Price</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php
        // PHP code to retrieve and display user data
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "sign_up_info";

        $conn = new mysqli($servername, $username, $password, $database);
        $sql = "SELECT * FROM rentals";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["userid"] . "</td>
                        <td>" . $row["username"] . "</td>
                        <td>" . $row["orderid"] . "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["model"] . "</td>
                        <td>" . $row["days"] . "</td>
                        <td>" . $row["price"] . "</td>
                        <td>" . $row["date"] . "</td>
                        <td><form method='post'><input type='hidden' name='orderid' value='" . $row["orderid"] . "'><input type='hidden' name='itemname' value='" . $row["name"] . "'><button class='delete-btn' type='submit' name='delete'>Complete</button></form></td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>0 results</td></tr>";
        }

        // Handle delete request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
            $orderId = $_POST["orderid"];
            $itemName = $_POST["itemname"];

            // Delete the order from rentals table first
            $sql = "DELETE FROM rentals WHERE orderid = $orderId";
            if ($conn->query($sql) === TRUE) {
                // After deleting, increment the quantity of the item in the items table
                $incrementSql = "UPDATE items SET quantity = quantity + 1 WHERE name = '$itemName'";
                if ($conn->query($incrementSql) === TRUE) {
                    echo "<script>alert('Order Completed and Quantity Updated Successfully');</script>";
                    echo "<script>window.location.href = 'order.php';</script>";
                } else {
                    echo "<script>alert('Error updating quantity: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Error deleting order: " . $conn->error . "');</script>";
            }
        }

        $conn->close();
        ?>
    </table>

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
                event.preventDefault();
                confirmationBox.style.display = 'block';
            });

            confirmLogout.addEventListener('click', () => {
                window.location.href = 'logout.php';
            });

            cancelLogout.addEventListener('click', () => {
                confirmationBox.style.display = 'none';
            });
        });
    </script>
</body>

</html>