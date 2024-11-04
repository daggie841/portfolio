<?php
session_start();


// // Check if admin is logged in
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     // Not logged in, redirect to login page
//     header("Location: adminlogin.php");
//     exit;
// }



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
     include_once 'config.php';
     //Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);


    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        
    }
    

    // Get the submitted form data
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Check if the password and confirm password match
    if ($pass !== $confirm_pass) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: adminsignup.php");
        exit();
    }

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, redirect to the create account page with an error
        $_SESSION['error'] = "Email already exists!";
        header("Location: adminsignup.php");
        exit();
    } else {
        // Insert the new admin account
        $stmt = $conn->prepare("INSERT INTO admin (email, pass) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();

        // Redirect to the login page
        $_SESSION['message'] = "Account created successfully!";
        header("Location: adminlogin.php");
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
    <title>Create Admin Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
           
        }
        .create-container {
          
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
        .create-container input[type="email"], 
        .create-container input[type="password"] {
            width: 80%;
            padding: 10px;
            box-sizing: border-box;
        }
        .create-container input[type="submit"] {
            border-radius: 100px;
            width: 60%;
            padding: 5px;
            background-color: #0056b3;
            border: none;
            color: black;
            cursor: pointer;
        }
        .create-container input[type="submit"]:hover {
            background-color: orange;
        }
        .create-container p {
            margin-top: 10px;
        }
        .create-container a {
            color: white;
            text-decoration: none;
        }
        .create-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="create-container">
        <h1>Create Admin Account</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="" method="POST">
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
                <input type="submit" value="Create Account">
            </div>
        </form>
        <!-- <p><a href="adminindex.php" style="color:black; background-color:#0056b3; border-radius:20px; padding: 5px 20px; text-decoration: none; transition: background-color skyblue;">Back to Dashboard</a></p> -->


    </div>
</body>
</html>
