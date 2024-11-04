<?php
include 'adminheader.php';
include 'config.php'; // Ensure this file has your database connection details
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM category WHERE id='$delete_id'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<span class='success-message'>Record deleted successfully</span>";
    } else {
        echo "<span class='error-message'>Error deleting record: " . $conn->error . "</span>";
    }
}

// Handle edit action
if (isset($_POST['edit_id']) && isset($_POST['edit_catname'])) {
    $edit_id = $_POST['edit_id'];
    $edit_catname = $_POST['edit_catname'];
    $edit_sql = "UPDATE category SET catname='$edit_catname' WHERE id='$edit_id'";
    if ($conn->query($edit_sql) === TRUE) {
        echo "<span class='success-message'>Record updated successfully</span>";
    } else {
        echo "<span class='error-message'>Error updating record: " . $conn->error . "</span>";
    }
}

// Handle add category action
if (isset($_POST['product_name'])) {
    $catname = $_POST['product_name'];
    $add_catname_sql = "INSERT INTO category (catname) VALUES ('$catname')";
    if ($conn->query($add_catname_sql) === TRUE) {
        echo "<span class='success-message'>Category added successfully</span>";
    } else {
        echo "<span class='error-message'>Error adding category: " . $conn->error . "</span>";
    }
}

// Fetch all category items from 'category' table
$sql = "SELECT id, catname FROM category";
$result = $conn->query($sql);

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
    .food-table input[type='text'], .food-table input[type='file'] {
        width: 100%;
        box-sizing: border-box;
        padding: 5px;
        margin: 2px 0;
    }
    .add-product-form {
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    .success-message {
        color: green;
        font-weight: bold;
    }
    .error-message {
        color: red;
        font-weight: bold;
    }
</style>";

echo "<table class='food-table'>
        <tr>
            <th>No</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>";
$counter = ++;
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <form method='post'>
                <td>".$counter."</td>
                <td><input type='text' name='edit_catname' value='".$row['catname']."'></td>
                <td>
                    <input type='hidden' name='edit_id' value='".$row['id']."'>
                    <button type='submit' name='edit'>Edit</button>
                    <button type='submit' name='delete_id' value='".$row['id']."'>Delete</button>
                </td>
            </form>
          </tr>";
    $counter++;
}
echo "</table>";

if ($result->num_rows == 0) {
    echo "No categories found";
}

// Display form for adding new category
echo "<div class='add-product-form'>
        <h3>Add New Category</h3>
        <form method='post'>
            <label for='product_name'>Category Name:</label>
            <input type='text' id='product_name' name='product_name' required>
            <button type='submit' name='add_category'>Add Category</button>
        </form>
      </div>";

$conn->close();
?>
<?php include 'footer.php'; ?>
