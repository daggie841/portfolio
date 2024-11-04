<?php
include 'header.php';
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Insert event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tableno']) && isset($_POST['foodid'])) {
    $tableno = $_POST['tableno'];
    $userid = $_SESSION['id'];
    $foodid = $_POST['foodid'];
    $foodname = $_POST['foodname'];
    $image = $_POST['image'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO `order` (tableno, userid, foodid, foodname, image, price, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $conn->error);
    }

    $stmt->bind_param("sisssds", $tableno, $userid, $foodid, $foodname, $image, $price, $description);

    if ($stmt->execute()) {
        $message = "Order placed successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

header("Location: preview_food.php?message=" . urlencode($message));
exit();
?>
