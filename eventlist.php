<?php
include 'header.php';

$message = "";
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Fetch all events
$result = $conn->query("SELECT * FROM event");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('img/liv.png');
            background-size: cover;
            background-repeat: no-repeat;
            opacity: 0.9;
        }
        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        .event {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            text-align: center;
        }
        .event img {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
        }
        .event .content {
            padding: 20px 0;
        }
        .event .name {
            font-size: 20px;
            font-weight: bold;
            color: #ff1493;
        }
        .event .date {
            color: #32cd32;
        }
        .event .price {
            color: #ff4500;
            font-weight: bold;
        }
        .event .detail {
            margin-top: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
        }
        .event .actions {
            margin-top: 10px;
        }
        .event .actions a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-right: 5px;
        }
        .event .actions a.edit {
            background-color: #007bff;
        }
        .event .actions a.edit:hover {
            background-color: #0056b3;
        }
        .event .actions a.delete {
            background-color: #dc3545;
        }
        .event .actions a.delete:hover {
            background-color: #c82333;
        }

        @media only screen and (max-width: 600px) {
            .event img {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
 
    <div class="container">
        <h2 style="text-align: center; color:darkpink;">Welcome to our events all people are welcome!</h2>

        <div class="events">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="event">
                    <img src="img/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                    <div class="content">
                        <div class="name"><?= $row['name'] ?></div>
                        <div class="date"><?= $row['date'] ?></div>
                        <div class="price"><?= $row['price'] ?></div>
                        <div class="detail"><?= $row['detail'] ?></div>
                        <div class="actions">
                            <a href="?edit=<?= $row['id'] ?>" class="edit">Buy Ticket</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php $conn->close(); ?>
   
</body>
</html>
 <?php include 'footer.php'; ?>