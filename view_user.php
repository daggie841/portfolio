<?php
include 'adminheader.php';
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user ID from the URL parameter
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];
    $sql = "SELECT * FROM `user` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        } else {
            die("User not found");
        }
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }
} else {
    die("User ID not provided");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        .user-details {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .user-details h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="user-details">
        <h2>User Details</h2>
        <p><strong>Firstname:</strong> <?php echo htmlspecialchars($user["firstname"]); ?></p>
        <p><strong>Lastname:</strong> <?php echo htmlspecialchars($user["lastname"]); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user["email"]); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user["phone"]); ?></p>
       <a href="foodinbox.php">Back to Food Order</a><br>
       <a href="roominbox.php">Back to Room Order</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
