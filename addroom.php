<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'adminheader.php';
// Include configuration and database connection
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $roomno = $_POST['roomno'];
    $price = $_POST['price'];
    $details = $_POST['details'];
    $status = $_POST['status']; // Added status field

    // Validate and handle image upload
    $targetDir = "img/"; // Directory where images will be stored
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    if(isset($_FILES["image"]) && isset($_POST["submit"])) {
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 90000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if(!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "The file ". htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                // SQL query to insert data into 'room' table
                $image = $targetFile; // Use $targetFile as the image path in the database
                $sql = "INSERT INTO room (roomno, price, details, image, status)
                        VALUES ('$roomno', '$price', '$details', '$image', '$status')";
                if ($conn->query($sql) === TRUE) {
                    // Redirect to roomlist.php upon successful insert
                    header("Location: roomlist.php");
                    exit; // Make sure no further code is executed
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}

$conn->close();
?>

<!-- HTML form to add a new room -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    Room Number: <input type="text" name="roomno" required><br>
    Image File: <input type="file" name="image" id="image" accept="image/*" required><br>
    Price: <input type="text" name="price" required><br>
    Details: <textarea name="details" rows="5" required></textarea><br>
    Status: 
    <select name="status" required>
        <option value="">Select status</option>
        <option value="available">Available</option>
        <option value="booked">Booked</option>
    </select><br>
    <input type="submit" name="submit" value="Add Room">
</form>
<?php include 'footer.php';?>

<style>
form {
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

input[type=text], input[type=file], textarea, select {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

input[type=submit]:hover {
    background-color: #45a049;
}
</style>
