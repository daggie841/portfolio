<?php
include 'header.php';

// Get message from the URL
$message = isset($_GET['message']) ? $_GET['message'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
                    .content {
                margin: 0;
                font-family: Arial, sans-serif;
                background-image: url('img/foo.png');
                background-size: cover;
                background-repeat: no-repeat;
                height: 70vh;
                opacity: 0.5;
            }

            .main-content {
                margin: 20px;
                font-family: Arial, sans-serif;
                opacity: 1;
                background-color: rgba(255, 255, 255, 0.5); /* Adjust this color as needed */
                padding: 20px; /* Add padding for better readability */
                border-radius: 10px; /* Optional: Add rounded corners */
            }

        .welcome-note {
            font-style: italic;
            font-weight: bold;
            font-size: 30px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            color: black;
        }
        .order-message {
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            color: green;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content">
    <div class="main-content">
        <div class="welcome-note">
            Thank you for ordering! We are delighted to have you here.<br>Your order will be ready in the next 3 to 7 minutes
        </div>
   
        <div class="order-message">
            <?php
            if ($message) {
                echo "<p>$message</p>";
            } else {
                echo "<p>No recent orders to display.</p>";
            }
            ?>
        </div>
        <a href="foodlist.php">Back to Food List</a>
    </div>
     </div>
    <footer>
        <script src="js/scripts.js"></script>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>
