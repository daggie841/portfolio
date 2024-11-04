<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        h2 {
            color: blue;
            font-size: 1.5em;
            margin-top: 0;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .button-back {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Booking Preview</h2>
        <p><strong>Room Number:</strong> <?php echo htmlspecialchars($_POST['roomno']); ?></p>
        <p><strong>Price:</strong> Ksh<?php echo htmlspecialchars($_POST['price']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($_POST['details']); ?></p>
        <p><strong>Check-In Date:</strong> <?php echo htmlspecialchars($_POST['checkin_date']); ?></p>
        <p><strong>Check-Out Date:</strong> <?php echo htmlspecialchars($_POST['checkout_date']); ?></p>
        <form method="POST" action="roomorder.php">
            <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($_POST['room_id']); ?>">
            <input type="hidden" name="roomno" value="<?php echo htmlspecialchars($_POST['roomno']); ?>">
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($_POST['price']); ?>">
            <input type="hidden" name="details" value="<?php echo htmlspecialchars($_POST['details']); ?>">
            <input type="hidden" name="checkin_date" value="<?php echo htmlspecialchars($_POST['checkin_date']); ?>">
            <input type="hidden" name="checkout_date" value="<?php echo htmlspecialchars($_POST['checkout_date']); ?>">
            <button type="submit" class="button">Confirm Booking</button>
        </form>
        <a href="javascript:history.back()" class="button button-back">Go Back</a>
    </div>
</body>
</html>
