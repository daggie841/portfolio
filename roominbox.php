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
    $delete_sql = "DELETE FROM `roomorder` WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Query to fetch room booking details, sorted by date in descending order
$sql = "SELECT id, room_id, userid, roomno, price, detail, date FROM `roomorder` ORDER BY date DESC";
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
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        
        @media (max-width: 600px) {
            table {
                border: 0;
            }
            thead {
                display: none;
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
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }
            td:nth-of-type(1):before { content: "No."; }
            td:nth-of-type(2):before { content: "Room No"; }
            td:nth-of-type(3):before { content: "Price"; }
            td:nth-of-type(4):before { content: "Detail"; }
            td:nth-of-type(5):before { content: "Client Info"; }
            td:nth-of-type(6):before { content: "Date"; }
            td:nth-of-type(7):before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <h2>Room Booking List</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Room No</th>
                <th>Price</th>
                <th>Detail</th>
                <th>Client Info</th>
                <th>Date</th>
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
                    echo "<td>" . htmlspecialchars($row["roomno"]) . "</td>";
                    echo "<td>Ksh" . htmlspecialchars($row["price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["detail"]) . "</td>";
                    echo "<td><a href='view_user.php?userid=" . htmlspecialchars($row["userid"]) . "' class='view-btn'>View Detail</a></td>";
                    echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
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
                echo "<tr><td colspan='7'>No bookings found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
<?php include 'footer.php'; ?>
