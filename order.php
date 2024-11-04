<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // Not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Debugging: Check if session variables are set
echo "User logged in: " . $_SESSION['user_logged_in'] . "<br>";

// Check if User ID is set in session
if (!isset($_SESSION['id'])) {
    echo "User ID not set in session. Please login.<br>";
    echo '<a href="login.php">Go to Login Page</a>';
    exit;
}

echo "User ID: " . $_SESSION['id'] . "<br>";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the food ID from the URL
$food_id = intval($_GET['food_id']);
$user_id = intval($_SESSION['id']); // Retrieve the user ID from the session

// Fetch food details using prepared statement
$stmt = $conn->prepare("SELECT * FROM food WHERE id = ?");
$stmt->bind_param("i", $food_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $foodname = $row['foodname'];
    $image = $row['image'];
    $price = $row['price'];
    $description = $row['description'];

    // For demonstration purposes, assuming $tableno needs to be added
    $tableno = 'tableno'; // Replace with actual table number if applicable

    // Insert order into the order table using prepared statement
    $stmt_order = $conn->prepare("INSERT INTO `order` (userid, foodid, foodname, image, price, description, tableno) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_order->bind_param("iissdsi", $user_id, $food_id, $foodname, $image, $price, $description, $tableno);

    if ($stmt_order->execute() === TRUE) {
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $stmt_order->error;
    }
} else {
    echo "Food item not found.";
}

$stmt->close();
$conn->close();
?>
