<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('Please log in first')</script>";
    echo "<script>window.location.href = 'guest.php'</script>";
    //header("Location: guest.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Css link for Font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!--Css link library-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!--Stylesheet-->
    <link rel="stylesheet" href="style.css">

    <title>Receipt</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .recipt {
            margin: 7rem 5rem;
        }

        .recipt h1 {
            margin: 1rem;
        }

        .recipt p {
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #28a745;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #218838;
        }

        #note {
            margin: 2rem;
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

        .note {
            background-color: #333;
            width: 70%;
            margin: 2rem;
            padding: 1rem;
            border-radius: 10px;
            left: -15%;
            transform: translateX(15%);
        }

        .note p {
            line-height: 30px;
            color: #fff;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!--Navigation Bar-->
    <nav class="nav-container">
        <div class="nav-items">

            <!--Logo-->
            <div class="first">
            <a href="/InRent/index.php"><img class="logo" src="inrentlogo.png"></a>
            </div>

            <!--Search Bar-->
            <div class="second">
                <div class="search">
                    <form action="search.php" method="GET">
                        <input class="search-input" type="text" placeholder="Search..." name="query">
                        <button style="border-radius: 5px; background-color: transparent; border:none; curser: pointer;"
                            type="submit"><i style="font-size: 20px;" class="fa fa-search"></i></button>
                    </form>
                </div>

                <!--Filter button with icon-->
                <div class="dropdown">
                    <button class="filter-btn" type="submit">Filter <i class="fa fa-filter"></i></button>
                    <div class="dropdown-items">
                        <a href="#">Model</a>
                        <a href="#">Cheapest</a>
                        <a href="#">Popular</a>
                    </div>
                </div>
            </div>

            <div class="third">
                <!-- <div class="drop-sign">
                        <a class="sign">Sign Up</a>
                        <div class="drop-form-sign">
                            <form class="sign-form" method="post" action="/InRent/signup.php">
                                <label for="username">Username</label>
                                <input placeholder="Choose a user name" type="text" required name="username">
                                <label for="email">Email Id</label>
                                <input placeholder="Enter you email" type="email" required name="email">
                                <label for="password">Password</label>
                                <input placeholder="Create a strong password" type="password" required name="password">

                                <button class="sign-btn" type="submit">Create</button>
                            </form>
                        </div>
                    </div>
                    <div class="drop-log">
                        <a class="log">Log In</a>
                        <div class="drop-form-log">
                            <form class="drop-form" method="post" action="/InRent/login.php">
                                <label for="username">Username</label>
                                <input placeholder="Enter your username" type="text" required name="username">
                                <label for="password">Password</label>
                                 <input placeholder="Enter your password" type="password" required name="password">

                                <button class="log-btn" type="submit">Log In</button>
                            </form>
                        </div>
                    </div>   -->

                    <div>
                        <a href='userrentals.php' class="userrentals">My Rentals</a>
                    </div>

                <!--Log out-->
                <div>
                    <a class="log" id="logout-link">Log Out</a>
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

    <?php
    // Fetch the username from the session
    $sessionUsername = $_SESSION['username'];

    // Database connection parameters
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $database = "sign_up_info";

    // Create a new database connection
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use a prepared statement to securely fetch the latest rental data for the user
    $sql = $conn->prepare("
    SELECT orderid, name, model, days, price, date 
    FROM rentals 
    WHERE username = ? 
    ORDER BY orderid DESC 
    LIMIT 1
");
    $sql->bind_param("s", $sessionUsername);

    // Execute the prepared statement
    $sql->execute();

    // Get the result
    $result = $sql->get_result();

    // Initialize variables
    $rentalId = null;
    $rentalName = null;
    $rentalModel = null;
    $rentalDays = null;
    $rentalPrice = null;
    $rentalDate = null;

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the row data
        $row = $result->fetch_assoc();
        $rentalId = $row["orderid"];
        $rentalName = $row["name"];
        $rentalModel = $row["model"];
        $rentalDays = $row["days"];
        $rentalPrice = $row["price"];
        $rentalDate = $row["date"];
    } else {
        echo "No rental records found.";
        $sql->close();
        $conn->close();
        exit(); // Stop further execution if no rental records are found
    }

    $sql->close();
    $conn->close();
    ?>

    <!--Recipt-->
    <section class="recipt">
        <div class="container">
            <h1>Receipt</h1>
            <p>Thank you for your rental, <?php echo htmlspecialchars($sessionUsername); ?>!</p>
            <table>
            <tr>
                    <th>Rental Id</th>
                    <td><?php echo htmlspecialchars($rentalId); ?></td>
                </tr>

                <tr>
                    <th>Item Name</th>
                    <td><?php echo htmlspecialchars($rentalName); ?></td>
                </tr>
                <tr>
                    <th>Model</th>
                    <td><?php echo htmlspecialchars($rentalModel); ?></td>
                </tr>
                <tr>
                    <th>Days Rented</th>
                    <td><?php echo htmlspecialchars($rentalDays); ?></td>
                </tr>
                <tr>
                    <th>Total Price</th>
                    <td>Rs.<?php echo htmlspecialchars($rentalPrice); ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?php echo htmlspecialchars($rentalDate); ?></td>
                </tr>
            </table>
            <div class="footer">
                <a href="index.php" class="btn">Rent Another Item</a>
            </div>
        </div>
    </section>

    <div class="note">
        <p>Thank you <?php echo htmlspecialchars($sessionUsername); ?> for choosing InRent. We provide quality and
            fair price. Please note that by renting from our establishment you are choosing to follow our terms and
            condition. Thank you for your support and understanding.</p> <br>
        <p><strong>Note:</strong> Please show this recipt as your proof of renting this item when picking up the vehicle
            at our
            vendor. You can show it via screenshot.
    </div>

    <div class="home-btn">
        <a id="home" href="index.php">Go to Home</a>
    </div>



    <!--Footer-->
    <section>
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

    </section>

    <script src="script.js"></script>

    <script src="search.js"></script>

</body>

</html>