<?php
ob_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Database connection
        include 'config.php';
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Your database operations here
    } catch (Exception $e) {
        // Handle database connection errors
        echo "Connection failed: " . $e->getMessage();
    }
}

// Handle logout action
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy(); // Destroy the session
    header('Location: adminlogin.php'); // Redirect to the login page
    exit(); // Ensure script termination after redirection
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="img/logo.png" alt="Restaurant & Hotel Logo">
        </div>
        <h1>WELCOME ADMIN</h1>
        <div class="grid_2" id="sidebar">
            <div class="box sidemenu">
                <div class="block" id="section-menu">
                    <ul class="section menu">
                        <a href="?action=logout">Logout</a><br>
                        <a href="adminsignup.php">Sign Up</a>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <nav>
        <ul class="nav-links">
            <li><a href="adminindex.php">Home</a></li>
            <li><a href="addroom.php">Add Room</a></li>
            <li><a href="roomlist.php">Room List</a></li>
            <li><a href="productadd.php">Add Food</a></li>
            <li><a href="productlist.php">Food list</a></li>
            <li><a href="catfood.php">F Category</a></li>
            <li><a href="roominbox.php">Room Orders</a></li>
            <li><a href="foodinbox.php">Food Orders</a></li>
            <li><a href="event.php">Events</a></li>
          </ul>
    </nav>
</body>
</html>
<style>body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 50px;
    background-color: deepskyblue;
    color: #fff;
}

.logo {
    width: 150px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
}

.logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

nav {
    background-color: #333;
}

.nav-links {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    text-align: center;
}

.nav-links li {
    display: inline;
}

.nav-links li a {
    display: inline-block;
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
}

.nav-links li a:hover {
    background-color: #555;
}

@media screen and (max-width: 600px) {
    .nav-links {
        display: block;
        text-align: center;
    }

    .nav-links li {
        display: block;
        margin-bottom: 10px;
    }
}
</style>