<?php
include 'adminheader.php';
$message = "";
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $price = $_POST['price'];
    $detail = $_POST['detail'];

    // Handle file upload
    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);

            // Prepare SQL and bind parameters
            $stmt = $conn->prepare("INSERT INTO event (name, date, image, price, detail) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $date, $image, $price, $detail);

            if ($stmt->execute()) {
                $message = "New event inserted successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    } else {
        $message = "File is not an image.";
    }
}

// Delete event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM event WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Event deleted successfully";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Edit event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $price = $_POST['price'];
    $detail = $_POST['detail'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]);
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $message = "File is not an image.";
        }
    }

    if (empty($message)) {
        if (isset($image)) {
            $stmt = $conn->prepare("UPDATE event SET name = ?, date = ?, image = ?, price = ?, detail = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $name, $date, $image, $price, $detail, $id);
        } else {
            $stmt = $conn->prepare("UPDATE event SET name = ?, date = ?, price = ?, detail = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $date, $price, $detail, $id);
        }

        if ($stmt->execute()) {
            $message = "Event updated successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
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
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        input[type="file"],
        input[type="number"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            resize: vertical;
        }
        input[type="submit"] {
            width: 100px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #ff0000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>EVENTS MANAGEMENT</h2>
        <h3>Add New Event</h3>
        <?php if (!empty($message)) { echo '<div class="message">' . $message . '</div>'; } ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="insert">
            <label for="name">Event Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="date">Event Date:</label>
            <input type="date" id="date" name="date" required>
            
            <label for="image">Event Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            
            <label for="price">Event Price:</label>
            <input type="number" id="price" name="price" step="0.00" required>

            <label for="detail">Event Detail:</label>
            <textarea id="detail" name="detail" rows="4"></textarea>
            
            <input type="submit" value="Insert Event">
        </form>

        <h2>Events List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><img src="img/<?= $row['image'] ?>" width="50" alt="<?= $row['name'] ?>"></td>
                    <td><?= $row['price'] ?></td>
                     <td><?= $row['detail'] ?></td>
                    <td class="actions">
                        <a href="?edit=<?= $row['id'] ?>">Edit</a> | 
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $edit_result = $conn->query("SELECT * FROM event WHERE id = $id");
            $edit_event = $edit_result->fetch_assoc();
        ?>
        <h2>Edit The Event</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= $edit_event['id'] ?>">
            <label for="name">Event Name:</label>
            <input type="text" id="name" name="name" value="<?= $edit_event['name'] ?>" required>
            
            <label for="date">Event Date:</label>
            <input type="date" id="date" name="date" value="<?= $edit_event['date'] ?>" required>
            
            <label for="image">Event Image (leave blank to keep current):</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <label for="price">Event Price:</label>
            <input type="number" id="price" name="price" step="0.00" value="<?= $edit_event['price'] ?>" required>

            <label for="detail">Event Detail:</label>
            <textarea id="detail" name="detail" rows="4"><?= $edit_event['detail'] ?></textarea>
            
            <input type="submit" value="Update Event">
        </form>
        <?php } ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
<?php include 'footer.php'; ?>
