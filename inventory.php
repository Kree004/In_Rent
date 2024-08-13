<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: guest.php");
    exit;
}

// Database connection parameters
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

// Fetch item details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM items WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        echo json_encode($item);
    }
    $stmt->close();
    exit;
}

// Delete item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM items WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    exit;
}

// Update item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $sql = "UPDATE items SET ";
    $params = [];
    $types = "";

    if (!empty($_POST["name"])) {
        $sql .= "name=?, ";
        $params[] = $_POST["name"];
        $types .= "s";
    }

    if (!empty($_POST["model"])) {
        $sql .= "model=?, ";
        $params[] = $_POST["model"];
        $types .= "s";
    }

    if (!empty($_POST["price"])) {
        $sql .= "price=?, ";
        $params[] = $_POST["price"];
        $types .= "d";
    }

    if (!empty($_POST["quantity"])) {
        $sql .= "quantity=?, ";
        $params[] = $_POST["quantity"];
        $types .= "i";
    }

    if (!empty($_POST["description"])) {
        $sql .= "description=?, ";
        $params[] = $_POST["description"];
        $types .= "s";
    }

    // Remove trailing comma and space
    $sql = rtrim($sql, ", ");
    $sql .= " WHERE id=?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo "<script>alert('Item updated successfully'); window.location.href='admin.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items List - Admin</title>
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

        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow-x: auto;
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

        .edit-btn {
            padding: 8px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin: 5px;
        }

        .delete-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            margin: 5px;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .edit-btn:hover {
            background-color: #0056b3;
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
            overflow-x: auto;
            padding: 20px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
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

            h2 {
                font-size: 18px;
                padding: 15px 0;
            }

            th,
            td {
                padding: 10px;
                font-size: 14px;
            }

            .edit-btn {
                padding: 6px 17px;
                font-size: 12px;
            }

            .delete-btn {
                padding: 6px 10px;
                font-size: 12px;
            }

            table {
                font-size: 14px;
            }

            img {
                width: 50px;
                height: 50px;
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
                font-size: 16px;
                padding: 12px 0;
            }

            th,
            td {
                padding: 8px;
                font-size: 12px;
            }

            .edit-btn {
                padding: 4px 14px;
                font-size: 10px;
            }

            .delete-btn {
                padding: 4px 8px;
                font-size: 10px;
            }

            table {
                font-size: 12px;
            }

            img {
                width: 40px;
                height: 40px;
            }
        }

        /* Edit Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: normal;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
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
        <h2>Items List - Admin</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Model</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php
            // SQL query to retrieve data from the database
            $sql = "SELECT * FROM items";
            $result = $conn->query($sql);

            // Check if there are rows returned by the query
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["model"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td><img src='" . $row["image"] . "' alt='Item Image' style='width: 100px; height: 100px;'></td>";
                    echo "<td>
                            <button class='edit-btn' onclick='editItem(" . $row["id"] . ")'>Edit</button>
                            <button class='delete-btn' onclick='deleteItem(" . $row["id"] . ")'>Delete</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No items found</td></tr>";
            }

            $conn->close();
            ?>
        </table>

        <!-- Edit Item Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editName">Name:</label>
                        <input type="text" id="editName" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editModel">Model:</label>
                        <input type="text" id="editModel" name="model" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editPrice">Price:</label>
                        <input type="number" id="editPrice" name="price" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editQuantity">Quantity:</label>
                        <input type="number" id="editQuantity" name="quantity" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editDescription">Description:</label>
                        <textarea id="editDescription" name="description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <script>
            function editItem(itemId) {
                // Retrieve item details using AJAX
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=" + itemId, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var item = JSON.parse(xhr.responseText);
                        // Populate the form fields with the item details
                        document.getElementById('editId').value = item.id;
                        document.getElementById('editName').value = item.name;
                        document.getElementById('editModel').value = item.model;
                        document.getElementById('editPrice').value = item.price;
                        document.getElementById('editQuantity').value = item.quantity;
                        document.getElementById('editDescription').value = item.description;
                        // Display the modal
                        document.getElementById('editModal').style.display = 'block';
                    }
                };
                xhr.send();
            }

            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
            }

            // Function to delete item
            function deleteItem(itemId) {
                if (confirm("Are you sure you want to delete this item?")) {
                    // Send AJAX request to delete item
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Reload the page to reflect changes
                            location.reload();
                            alert('Item deleted sucessfully.')
                        }
                    };
                    xhr.send("delete_id=" + itemId);
                }
            }

            document.getElementById('editForm').addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent the form from submitting the default way

                var editId = document.getElementById('editId').value;
                var editName = document.getElementById('editName').value;
                var editModel = document.getElementById('editModel').value;
                var editPrice = document.getElementById('editPrice').value;
                var editQuantity = document.getElementById('editQuantity').value;
                var editDescription = document.getElementById('editDescription').value;

                // Prepare form data
                var formData = "id=" + editId;
                if (editName) formData += "&name=" + editName;
                if (editModel) formData += "&model=" + editModel;
                if (editPrice) formData += "&price=" + editPrice;
                if (editQuantity) formData += "&quantity=" + editQuantity;
                if (editDescription) formData += "&description=" + editDescription;

                // Send AJAX request to update item
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Close the modal and reload the page to reflect changes
                        closeEditModal();
                        location.reload();
                        alert('Change have been comitted.');
                    }
                };
                xhr.send(formData);
            });
        </script>
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