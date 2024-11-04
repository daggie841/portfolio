<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include header and configuration
include 'adminheader.php';
include 'config.php';

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from catefood table
$sqlCategories = "SELECT catfood FROM catefood";
$resultCategories = $conn->query($sqlCategories);

// Initialize uploadOk variable for file upload handling
$uploadOk = 1;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $foodname = $_POST['foodname'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $catfood_name = $_POST['catfood']; // Corrected variable name to match category name

    // File upload handling
    $targetDir = "img/"; // Directory where uploaded files will be stored
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            // SQL query to insert data into 'food' table
           // SQL query to insert data into 'food' table
$image = $targetFile;
$sql = "INSERT INTO food (foodname, description, image, price, status, catfood)
        VALUES ('$foodname', '$description', '$image', '$price', '$status', '$catfood_name')";
if ($conn->query($sql) === TRUE) {
    // Redirect to productlist.php upon successful insert
    header("Location: productlist.php");
    exit; // Make sure no further code is executed
} else {
     echo "Sorry, there was an error uploading your file.";
        }
    }}
}

$conn->close();
?>

<!-- HTML form to add a new food item -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    Food Name: <input type="text" name="foodname" required><br>
    Image File: <input type="file" name="image" id="image" accept="image/*" required><br>
    Price: <input type="text" name="price" required><br>
    Description: <input type="text" name="description" required><br>
    Status:
    <select name="status" required>
        <option value="">Select status</option>
        <option value="available">Available</option>
        <option value="Not available">Not available</option>
    </select><br>
    Category:
    <select name="catfood" required>
        <option value="">Select category</option>
        <?php
        // Display categories from catefood table as options
        if ($resultCategories->num_rows > 0) {
            while ($row = $resultCategories->fetch_assoc()) {
                echo '<option value="' . $row['catfood'] . '">' . $row['catfood'] . '</option>';
            }
        }
        ?>
    </select><br>
    <input type="submit" name="submit" value="Add Food">
</form>
<?php include 'footer.php'; ?>

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
