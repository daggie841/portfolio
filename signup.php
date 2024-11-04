<?php
session_start();

// Check if admin is logged in
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     // Not logged in, redirect to login page
//     header("Location: adminlogin.php");
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    include_once 'config.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the submitted form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Check if the password and confirm password match
    if ($pass !== $confirm_pass) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: signup.php");
        exit();
    }

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, redirect to the create account page with an error
        $_SESSION['error'] = "Email already exists!";
        header("Location: signup.php");
        exit();
    } else {
        // Insert the new admin account
        $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, phone, email, pass) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $firstname, $lastname, $phone, $email, $pass);
        $stmt->execute();

        // Redirect to the login page
        $_SESSION['message'] = "Account created successfully!";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .create-container {
            background-color: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .create-container h1 {
            margin-bottom: 20px;
        }
        .create-container div {
            margin-bottom: 15px;
        }
        .create-container input[type="text"],
        .create-container input[type="email"],
        .create-container input[type="password"],
        .create-container input[type="tel"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .create-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #0056b3;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        .create-container input[type="submit"]:hover {
            background-color: orange;
        }
        .create-container p {
            margin-top: 10px;
        }
        .create-container a {
            color: #0056b3;
            text-decoration: none;
        }
        .create-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="create-container">
        <h1>Create User Account</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="" method="POST">
            <div>
                <input type="text" name="firstname" placeholder="Enter first name" required>
            </div>
            <div>
                <input type="text" name="lastname" placeholder="Enter last name" required>
            </div>
            <div>
                <input type="tel" name="phone" placeholder="Enter phone" required>
            </div>
            <div>
                <input type="email" name="email" placeholder="Enter Email" required>
            </div>
            <div>
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>
            <div>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div>
                <input type="submit" value="Sign up">
            </div>
        </form>
        <p><a href="index.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
