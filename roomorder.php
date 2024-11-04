<?php
include 'header.php';
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch and sanitize POST data
    $room_id = isset($_POST['room_id']) ? $_POST['room_id'] : null;
    $roomno = isset($_POST['roomno']) ? $_POST['roomno'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $details = isset($_POST['details']) ? $_POST['details'] : null;
    $checkin_date = isset($_POST['checkin_date']) ? $_POST['checkin_date'] : null;
    $checkout_date = isset($_POST['checkout_date']) ? $_POST['checkout_date'] : null;

    // Get userid from session
    $userid = $_SESSION['id'];
    
    // Get the current date
    $date = date('Y-m-d H:i:s');

    // Check for null values and set defaults if needed
    $details = $details ?? ''; // Default to empty string if null

    // Insert booking data into roomorder table
    $sql = "INSERT INTO roomorder (room_id, roomno, price, detail, checkin_date, checkout_date, userid, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("isssssss", $room_id, $roomno, $price, $details, $checkin_date, $checkout_date, $userid, $date);

    if ($stmt->execute()) {
       echo "<p style='color: green; font-weight: bold; text-align: center; font-style: italic; background-image: url(your-image-url.jpg); background-size: cover; padding: 20px;'>
        Your booking was successful!
    </p>";
echo "<p style='text-align: center; font-style: italic; background-image: url(img/2 beds.png); background-size: cover; padding: 20px;'>
        Welcome to our hotel and restaurant! We are delighted to have you with us and look forward to providing you with the best service possible. Enjoy our comfortable rooms and delicious meals. We hope you have a memorable stay!
    </p>";
    } else {
        echo "Error executing statement: " . $stmt->error;
    }

    $stmt->close();
}

// Close database connection
$conn->close();
include 'footer.php';
?>
