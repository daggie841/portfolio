<?php
include 'adminheader.php';
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete order if delete button is clicked
if (isset($_POST['delete'])) {
    $order_id = $_POST['order_id'];
    $delete_sql = "DELETE FROM `order` WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
}

// Query to fetch order details, sorted by order_date in descending order
$sql = "SELECT id, foodname, image, price, description, tableno, order_date FROM `order` ORDER BY order_date DESC";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            border-radius: 5px;
            max-width: 50px;
            max-height: 50px;
        }
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        @media (max-width: 600px) {
            th, td {
                display: block;
                width: 100%;
            }
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                border: 1px solid #ddd;
                margin-bottom: 10px;
                display: block;
                width: 100%;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            td:before {
                position: absolute;
                top: 6px;
                left: 6px;
                width: 25%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }
            td:nth-of-type(1):before { content: "No."; }
            td:nth-of-type(2):before { content: "Food Name"; }
            td:nth-of-type(3):before { content: "Image"; }
            td:nth-of-type(4):before { content: "Price"; }
            td:nth-of-type(5):before { content: "Description"; }
            td:nth-of-type(6):before { content: "Table No"; }
            td:nth-of-type(7):before { content: "Client Info"; }
            td:nth-of-type(8):before { content: "Order Date"; }
            td:nth-of-type(9):before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <h2>Food Order List</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Food Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Description</th>
                <th>Table No</th>
                <th>Client Info</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $counter = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter . "</td>";
                    echo "<td>" . htmlspecialchars($row["foodname"]) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["foodname"]) . "'></td>";
                    echo "<td>Ksh" . htmlspecialchars($row["price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["tableno"]) . "</td>";
                    echo "<td><a href='view_user.php?user_id=" . htmlspecialchars($row["id"]) . "'>View Details</a></td>";
                    echo "<td>" . htmlspecialchars($row["order_date"]) . "</td>";
                    echo "<td>";
                    echo "<form method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='order_id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' name='delete' value='Delete' class='delete-btn'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='9'>No orders found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
<?php include 'footer.php'; ?>
