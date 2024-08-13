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

    <style>
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
    </style>

    <title>InRent</title>
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
                    <div class="dropdown-items" style="background-color: red;">
                        <a>Name</a>
                        <a>Model</a>
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

                <!--Log out-->
                <!-- <div>
                    <a class="log" id="logout-link">Log Out</a>
                </div>

                <div id="confirmation-box" class="confirmation-box">
                    <p>Are you sure you want to log out?</p>
                    <button id="confirm-logout">Yes</button>
                    <button id="cancel-logout">No</button>
                </div> -->

                <!--Contact-->
                <a href="tel:0123456789"> <i class="fa fa-phone"></i> Call Us</a>
                <a href="mailto:kirikirikapali@gmail.com"> <i class="fa fa-envelope"></i> Mail Us</a>
            </div>
        </div>
    </nav>

    <!--Cards-->
    <section id='item' style="background-color: transparent; margin-top: 10rem;">
        <h2>Our Popular Items</h2>
        <div class="grid-container">
            <?php
            // Connect to the database and fetch data
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "sign_up_info";

            $conn = new mysqli($servername, $username, $password, $database);


            // Get the search query from URL
            $search_query = $_GET['query'];

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM items WHERE name LIKE ? OR model LIKE ?";
            $stmt = $conn->prepare($sql);
            $search_param = "%" . $search_query . "%";
            $stmt->bind_param("ss", $search_param, $search_param);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="grid-items">';
                    echo '<img class="card-bikes" src="http://localhost/InRent/' . $row["image"] . '">';
                    echo '<div class="card-info">';
                    echo '<h3><span class="bike-name">' . $row["name"] . '</span>: <span class="bike-model">' . $row["model"] . '</span></h3>';
                    echo '<hr>';
                    echo '<p>' . $row["description"] . '</p>';
                    echo '<hr>';
                    echo '</div>';
                    echo '<div class="card-price">';
                    echo '<input class="quantity" type="number" min="1">';
                    echo '<p>Price: Rs <span class="price" data-default-price="' . $row["price"] . '">' . $row["price"] . '</span></p>';
                    echo '</div>';
                    echo '<button type="submit" class="card-btn">Rent</button>';
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>
        </div>
    </section>

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

</body>

</html>