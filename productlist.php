<?php
include 'adminheader.php';
// Connect to your database (adjust credentials as needed)
include 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from catefood table
$sqlCategories = "SELECT catfood FROM catefood";
$resultCategories = $conn->query($sqlCategories);
$categories = [];
if ($resultCategories->num_rows > 0) {
    while ($cat = $resultCategories->fetch_assoc()) {
        $categories[] = $cat;
    }
}

// Handle delete action
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM food WHERE id=?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Handle edit action
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $edit_foodname = $_POST['edit_foodname'];
    $edit_price = $_POST['edit_price'];
    $edit_description = $_POST['edit_description'];
    $edit_catfood = $_POST['catefood']; // Use category name directly
    $edit_status = $_POST['edit_status'];
    $edit_image = $_POST['current_image'];

    // Validate and handle image upload
    $targetDir = "img/"; // Directory where images will be stored
    $uploadOk = 1;

    if (isset($_FILES['edit_image']) && !empty($_FILES['edit_image']['tmp_name'])) {
        $targetFile = $targetDir . basename($_FILES["edit_image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["edit_image"]["tmp_name"]);
        if ($check !== false) {
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

    $edit_sql = "UPDATE food SET foodname=?, image=?, price=?, description=?, catfood=?, status=? WHERE id=?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("ssdssss", $edit_foodname, $edit_image, $edit_price, $edit_description, $edit_catfood, $edit_status, $edit_id);
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all food items from 'food' table
$sql = "SELECT id, foodname, image, price, description, catfood, status FROM food";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display each food item in a table
    echo "<style>
            .food-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            .food-table th, .food-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .food-table th {
                background-color: #f2f2f2;
            }

            .food-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .food-table tr:hover {
                background-color: #ddd;
            }

            .food-image {
                width: 50px;
                height: auto;
                border-radius: 5px;
            }

            .food-table button {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 5px 10px;
                text-decoration: none;
                cursor: pointer;
                border-radius: 5px;
                margin: 2px;
            }

            .food-table button:hover {
                background-color: #0056b3;
            }

            .food-table input[type='text'], .food-table input[type='file'], .food-table select {
                width: 100%;
                box-sizing: border-box;
                padding: 5px;
                margin: 2px 0;
            }

            @media only screen and (max-width: 600px) {
                .food-table th, .food-table td {
                    font-size: 12px;
                    padding: 4px;
                }

                .food-image {
                    width: 30px;
                }

                .food-table button {
                    padding: 3px 5px;
                    font-size: 12px;
                }
            }
        </style>";

    echo "<table class='food-table'>
            <tr>
                <th>No</th>
                <th>Food Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";
    
    $counter = 1; // Initialize counter
    while ($row = $result->fetch_assoc()) {
        $statusColor = ($row['status'] == 'Available') ? 'green' : 'red';
        echo "<tr>
                <form method='post' enctype='multipart/form-data'>
                    <td>".$counter."<input type='hidden' name='edit_id' value='".$row['id']."'></td>
                    <td><input type='text' name='edit_foodname' value='".$row['foodname']."'></td>
                    <td>
                        <img src='".$row['image']."' alt='".$row['foodname']."' class='food-image'>
                        <input type='file' name='edit_image'>
                        <input type='hidden' name='current_image' value='".$row['image']."'>
                    </td>
                    <td><input type='text' name='edit_price' value='".$row['price']."'></td>
                    <td><input type='text' name='edit_description' value='".$row['description']."'></td>
                    <td>
                        <select name='catefood' required>
                            <option value=''>Select category</option>";
        foreach ($categories as $cat) {
            echo '<option value="' . $cat['catfood'] . '"'.($row['catfood'] == $cat['catfood'] ? ' selected' : '').'>' . $cat['catfood'] . '</option>';
        }
        echo "          </select>
                    </td>
                    <td style='color: $statusColor;'>
                        <select name='edit_status'>
                            <option value='Available'".($row['status'] == 'Available' ? ' selected' : '').">Available</option>
                            <option value='Not available'".($row['status'] == 'Not available' ? ' selected' : '').">Not available</option>
                        </select>
                    </td>
                    <td>
                        <button type='submit' name='submit'>Edit</button>
                    </td>
                </form>
                <form method='post'>
                    <td>
                        <button type='submit' name='delete_id' value='".$row['id']."'>Delete</button>
                    </td>
                </form>
              </tr>";
        $counter++; // Increment counter for each row
    }
    echo "</table>";
} else {
    echo "No food items found";
}

$conn->close();
?>
<?php include 'footer.php'; ?>
