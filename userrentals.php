<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: guest.php");
    exit;
}

// Get the logged-in user's username
$sessionUsername = $_SESSION['username'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "sign_up_info";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $orderId = $_POST["orderid"];
    $itemName = $_POST["name"];
    
    // Delete the rental item from the rentals table
    $sql = "DELETE FROM rentals WHERE orderid = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $orderId, $sessionUsername);
    
    if ($stmt->execute()) {
        // Increment the quantity of the item in the items table
        $incrementSql = "UPDATE items SET quantity = quantity + 1 WHERE name = ?";
        $incrementStmt = $conn->prepare($incrementSql);
        $incrementStmt->bind_param("s", $itemName);
        $incrementStmt->execute();

        echo "<script>alert('Rental item deleted successfully.');</script>";
        echo "<script>window.location.href = 'myrentals.php';</script>";
    } else {
        echo "<script>alert('Error deleting rental item: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My Rentals</title>
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
            margin-top: 5rem;
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

        .home-btn {
            display: flex;
            justify-content: center;
            margin: 1rem;
        }

        #home {
            border: 1px solid rgb(20, 20, 99);
            padding: 0.5rem;
            background-color: rgb(157, 41, 41);
            color: #fff;
            border-radius: 5px;
        }

        #home:hover {
            background-color: rgb(20, 20, 99);
            border: none;
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
<nav class="nav-container">
            <div class="nav-items">

                <!--Logo-->
                <div class="first">
                    <img class="logo" src="inrentlogo.png">
                </div>

                <!--Search Bar-->
                <div class="second">
                    <div class="search">
                        <form action="search.php" method="GET">
                            <input class="search-input" type="text" placeholder="Search..." name="query">
                            <button
                                style="border-radius: 5px; background-color: transparent; border:none; curser: pointer;"
                                type="submit"><i style="font-size: 20px;" class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <!--Filter button with icon-->
                    <div class="dropdown">
                        <button class="filter-btn" type="submit">Filter <i class="fa fa-filter"></i></button>
                        <div class="dropdown-items">
                            <a>Name</a>
                            <a>Model</a>
                        </div>
                    </div>
                </div>

                <div class="third">
                    <!--Log out-->
                    <div>
                        <a class="log" id="logout-link">Log Out</a>
                    </div>

                    <div>
                        <a href='userrentals.php' class="userrentals">My Rentals</a>
                    </div>

                    <div id="confirmation-box" class="confirmation-box">
                        <p>Are you sure you want to log out?</p>
                        <button id="confirm-logout">Yes</button>
                        <button id="cancel-logout">No</button>
                    </div>

                    <!--Contact-->
                    <a href="tel:0123456789"> <i class="fa fa-phone"></i> Call Us</a>
                    <a href="mailto:kirikirikapali@gmail.com"> <i class="fa fa-envelope"></i> Mail Us</a>
                </div>
            </div>
        </nav>

    <h1>My Rentals</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Name</th>
            <th>Model</th>
            <th>Days</th>
            <th>Price</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php
        // Retrieve the user's rented items
        $sql = "SELECT * FROM rentals WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $sessionUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["orderid"] . "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["model"] . "</td>
                        <td>" . $row["days"] . "</td>
                        <td>" . $row["price"] . "</td>
                        <td>" . $row["date"] . "</td>
                        <td><form method='post'>
                            <input type='hidden' name='orderid' value='" . $row["orderid"] . "'>
                            <input type='hidden' name='name' value='" . $row["name"] . "'>
                            <button class='delete-btn' type='submit' name='delete'>Delete</button>
                        </form></td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No rentals found</td></tr>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </table>

    <div class="home-btn">
        <a id="home" href="index.php">Go to Home</a>
    </div>

    <footer>

<h1>Contact Us</h1>
<hr>
<p>Use the contact provided below to directly contact us for any type of inquiries.</p>
<p>Feel free to get in touch with us via any official platform.</p>
<hr>

<!--Socials-->
<div class="socials">
    <p><i class="fa fa-map-marker"></i> Gwarko, Lalitpur</p>
    <p><i class="fa fa-phone"></i> 123456789, 123456789</p>
    <p><i class="fa fa-instagram"></i> @instagram</p>
    <p><i class="fa fa-facebook"></i> In Rent</p>
</div>

<!--Live Map-->
<iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1766.8240995971505!2d85.33165813065317!3d27.66635413457102!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19ddc182f339%3A0xec009106dd012081!2sGwarko%2C%20Lalitpur!5e0!3m2!1sen!2snp!4v1709130137710!5m2!1sen!2snp"
    class="map" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

<!--Copyright-->
<p class="copyright">&copy; 2024 Your Website Name. All Rights Reserved.</p>

</footer>
    <script src="script.js"></script>
</body>
</html>
