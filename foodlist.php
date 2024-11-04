<?php
ob_start();
include 'header.php';
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories
$sql_categories = "SELECT DISTINCT catfood FROM food";
$result_categories = $conn->query($sql_categories);
if ($result_categories === false) {
    die("Error fetching categories: " . $conn->error);
}

// Fetch food items
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$sql_food = "SELECT id, foodname, image, price, description, status, catfood FROM food";
if (!empty($category_filter)) {
    $sql_food .= " WHERE catfood = '".$conn->real_escape_string($category_filter)."'";
}
$sql_food .= " ORDER BY id DESC";
$result_food = $conn->query($sql_food);
if ($result_food === false) {
    die("Error fetching food items: " . $conn->error);
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food List</title>
    <style>
        /* Styles as defined in the original code */
    </style>
</head>
<body>
    <div class="main">
        <div class="sidebar">
            <h2>Categories</h2>
            <ul>
                <li><a href="foodlist.php">All</a></li>
                <?php
                if ($result_categories->num_rows > 0) {
                    while ($row = $result_categories->fetch_assoc()) {
                        echo '<li><a href="foodlist.php?category=' . urlencode($row['catfood']) . '">' . htmlspecialchars($row['catfood']) . '</a></li>';
                    }
                } else {
                    echo '<li>No categories found.</li>';
                }
                ?>
            </ul>
        </div>
        <div class="main-content">
            <div class="container">
                <?php
                if ($result_food->num_rows > 0) {
                    while ($row = $result_food->fetch_assoc()) {
                        $statusClass = ($row['status'] == 'available') ? 'status-available' : 'status-Not-available';
                        $isDisabled = ($row['status'] == 'Not available') ? 'disabled' : '';
                        echo '<div class="product">';
                        echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['foodname']) . '">';
                        echo '<h2>' . htmlspecialchars($row['foodname']) . '</h2>';
                        echo '<p>Price: Ksh' . htmlspecialchars($row['price']) . '</p>';
                        echo '<p>Description: ' . htmlspecialchars($row['description']) . '</p>';
                        echo '<p class="' . $statusClass . '">Status: ' . htmlspecialchars($row['status']) . '</p>';
                        echo '<p>Category: ' . htmlspecialchars($row['catfood']) . '</p>';
                        echo '<form method="POST" action="order_food.php">';
                        echo '<input type="hidden" name="foodid" value="' . $row['id'] . '">';
                        echo '<input type="hidden" name="foodname" value="' . htmlspecialchars($row['foodname']) . '">';
                        echo '<input type="hidden" name="image" value="' . htmlspecialchars($row['image']) . '">';
                        echo '<input type="hidden" name="price" value="' . htmlspecialchars($row['price']) . '">';
                        echo '<input type="hidden" name="description" value="' . htmlspecialchars($row['description']) . '">';
                        echo '<br>Table No: <input type="text" name="tableno" required>';
                        echo '<button type="submit" class="order-now ' . $isDisabled . '">Order Now</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo "No food items found.";
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
    <footer>
        <script src="js/scripts.js"></script>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 250px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .sidebar h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }
        .product {
            width: calc(33.333% - 20px);
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .product img {
            width: 90%;
            height: 300px;
            object-fit: cover;
            border-radius: 3%;
        }
        .product h2 {
            font-size: 1.2em;
            margin-top: 10px;
        }
        .product p {
            margin: 5px 0;
        }
        .status-available {
            font-weight: bold;
            color: green;
        }
        .status-Not-available {
            font-weight: bold;
            color: red;
        }
        .order-now {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            border: none;
            cursor: pointer;
        }
        .order-now.disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        @media (max-width: 768px) {
            .product {
                width: calc(50% - 20px);
            }
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }
        @media (max-width: 480px) {
            .product {
                width: calc(100% - 20px);
            }
        }
    </style>
