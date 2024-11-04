<?php
include 'header.php';
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch products from the 'room' table
$sql = "SELECT id, roomno, price, details, image, status FROM room";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room List</title>
    <style>
        /* General CSS */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .product {
            display: flex;
            width: 100%;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .product img {
            flex: 0 0 200px;
            width: 600px;
            height: 400px; /* Fixed height for consistency */
            object-fit: cover; /* Ensures the image covers the container */
            border-radius: 5px;
            margin-right: 20px;
        }

        .product-details {
            flex: 1;
        }

        .product h2 {
            color: blue;
            font-size: 1.2em;
            margin-top: 0;
        }

        .product p {
            font-weight: bold;
            margin: 5px 0;
            font-size: 16px;
            line-height: 1.6;
        }

        .status-available {
            font-weight: bold;
            color: green;
        }

        .status-booked {
            font-weight: bold;
            color: red;
        }

        .book-now {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .booking-form {
            margin-top: 20px;
        }

        .booking-form label {
            display: block;
            margin: 10px 0 5px;
        }

        .booking-form input {
            display: block;
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .product {
                flex-direction: column;
                align-items: center;
            }

            .product img {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 480px) {
            .product img {
                width: 100%;
                height: 30vh; /* Corrected height unit */
                margin-right: 0;
            }
            .product {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <p style='text-align: center; font-style: italic; padding: 20px;'>
        Welcome to our hotel and restaurant! We are delighted to have you with us and look forward to providing you with the best service possible. Enjoy our comfortable rooms and delicious meals. We hope you have a memorable stay!</p>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $statusClass = ($row['status'] == 'available') ? 'status-available' : 'status-booked';
                echo '<div class="product">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['roomno'] . '">';
                echo '<div class="product-details">';
                echo '<h2>' . $row['roomno'] . '</h2>';
                echo '<p>Price: Ksh' . $row['price'] . '</p>';
                echo '<p>Description: ' . $row['details'] . '</p>';
                echo '<p class="' . $statusClass . '">Status: ' . $row['status'] . '</p>';
                
                if ($row['status'] == 'available') {
                    echo '<form method="POST" action="booking_preview.php">';
                    echo '<input type="hidden" name="room_id" value="' . $row['id'] . '">';
                    echo '<input type="hidden" name="roomno" value="' . $row['roomno'] . '">';
                    echo '<input type="hidden" name="price" value="' . $row['price'] . '">';
                    echo '<input type="hidden" name="details" value="' . $row['details'] . '">';
                    echo '<input type="hidden" name="image" value="' . $row['image'] . '">';
                    echo '<div class="booking-form">';
                    echo '<label for="checkin_date">Check-In Date:</label>';
                    echo '<input type="date" id="checkin_date" name="checkin_date" required>';
                    echo '<label for="checkout_date">Check-Out Date:</label>';
                    echo '<input type="date" id="checkout_date" name="checkout_date" required>';
                    echo '<button type="submit" class="book-now">Book Now</button>';
                    echo '</div>'; // Close .booking-form
                    echo '</form>';
                }
                
                echo '</div>'; // Close .product-details
                echo '</div>'; // Close .product
            }
        } else {
            echo "No rooms found.";
        }

        // Close database connection
        $conn->close();
        ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
