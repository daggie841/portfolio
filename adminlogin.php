<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Database connection
        include_once 'config.php';

        // Create connection
        
$conn = new mysqli($servername, $username, $password, $dbname);


        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the submitted form data
        $email = $_POST['email'];
        $pass = $_POST['password'];

        // Prepare and execute the query to check if the admin exists
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND pass = ?");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Admin exists, set session variables and redirect to the dashboard
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin'] = $email;
            header("Location: adminindex.php");
            exit();
        } else {
            // Admin does not exist, show an alert
            echo("<script>alert('Invalid email or password');</script>");
        }
    } catch (Exception $e) {
        echo 'Connection failed: ' . $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Styles omitted for brevity */
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <form action="" method="POST">
            <div>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div>
                <input type="submit" value="Log in">
                <a href="forget-password.php">Forgot password?</a>
            </div>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: white;
        }
        .login-container {
            background-color: #fff;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h1 {
            margin-bottom: 20px;
        }
        .login-container div {
            margin-bottom: 15px;
        }
        .login-container input[type="email"], 
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            color: white;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .login-container p {
            margin-top: 10px;
        }
        .login-container a {
            color: #007BFF;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
