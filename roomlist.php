<?php
include 'adminheader.php';
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Handle delete action
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM room WHERE id='$delete_id'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Handle edit action
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $edit_roomno = $_POST['edit_roomno'];
    $edit_price = $_POST['edit_price'];
    $edit_details = $_POST['edit_details'];
    $edit_status = $_POST['edit_status'];
    $edit_image = isset($_POST['current_image']) ? $_POST['current_image'] : '';
   

    // Validate and handle image upload
    $targetDir = "img/"; // Directory where images will be stored
    $uploadOk = 1;

    if (isset($_FILES['edit_image']) && !empty($_FILES['edit_image']['tmp_name'])) {
        $targetFile = $targetDir . basename($_FILES["edit_image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["edit_image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["edit_image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["edit_image"]["tmp_name"], $targetFile)) {
                $edit_image = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    $edit_sql = "UPDATE room SET roomno='$edit_roomno', image='$edit_image', price='$edit_price', details='$edit_details', status='$edit_status' WHERE id='$edit_id'";
    if ($conn->query($edit_sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch all room items from 'room' table
$sql = "SELECT room.id, room.roomno, room.image, room.price, room.details, room.status FROM room";
$result = $conn->query($sql);

if ($result === false) {
    die("Error fetching rooms: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Display each room item in a table
    echo "<style>
            .room-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            .room-table th, .room-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .room-table th {
                background-color: #f2f2f2;
            }

            .room-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .room-table tr:hover {
                background-color: #ddd;
            }

            .room-image {
                width: 50px;
                height: auto;
                border-radius: 5px;
            }

            .room-table button {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 5px 10px;
                text-decoration: none;
                cursor: pointer;
                border-radius: 5px;
                margin: 2px;
            }

            .room-table button:hover {
                background-color: #0056b3;
            }

            .room-table input[type='text'], .room-table input[type='file'], .room-table textarea, .room-table select {
                width: 100%;
                box-sizing: border-box;
                padding: 5px;
                margin: 2px 0;
            }
        </style>";

    echo "<table class='room-table'>
            <tr>
                <th>No</th>
                <th>Room Number</th>
                <th>Price</th>
                <th>Details</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";

    $num = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <form method='post' enctype='multipart/form-data'>
                    <td>".$num++."</td>
                    <td><input type='text' name='edit_roomno' value='".$row['roomno']."'></td>
                    <td><input type='text' name='edit_price' value='".$row['price']."'></td>
                    <td><textarea name='edit_details'>".$row['details']."</textarea></td>
                    <td>
                        <img src='".$row['image']."' alt='".$row['roomno']."' class='room-image'>
                        <input type='file' name='edit_image'>
                        <input type='hidden' name='current_image' value='".$row['image']."'>
                    </td>
                    <td>
                        <select name='edit_status'>
                            <option value='available' ".($row['status'] == 'available' ? 'selected' : '').">Available</option>
                            <option value='booked' ".($row['status'] == 'booked' ? 'selected' : '').">Booked</option>
                        </select>
                    </td>
                    <td>
                        <button type='submit' name='edit_id' value='".$row['id']."'>Edit</button>
                        <button type='submit' name='delete_id' value='".$row['id']."'>Delete</button>
                    </td>
                </form>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No room items found";
}

$conn->close();
?>
<?php include 'footer.php'; ?>
