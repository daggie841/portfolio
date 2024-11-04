<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    include_once 'config.php';
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the submitted form data
    $email = $_POST['email'];

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo  '<span style="color: red; font-weight: bold;">Invalid email format';
        exit;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user) {
        echo  '<span style="color: red; font-weight: bold;">Email not registered';
        exit;
    }

    // Generate token
    $token = bin2hex(random_bytes(32)); // Generate a secure random token
    $token_expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token valid for 1 hour

    // Store token in the database
    $stmt = $conn->prepare("UPDATE user SET reset_token = ?, token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $token_expiry, $email);
    if ($stmt->execute()) {
        // Send email with reset link
        $reset_link = "http://cleanwavess.com/usereset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "To reset your password, click on this link: $reset_link";
        $headers = "From: no-reply@yourdomain.com";
        
        if (mail($email, $subject, $message, $headers)) {
            echo  '<span style="color: green; font-weight: bold;">Password reset link has been sent to your email.';
        } else {
            echo  '<span style="color: red; font-weight: bold;">Failed to send email.';
        }
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Password Reset</title>
    <style>
        /* General resets */
        body, html {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="email"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: calc(100% - 22px); /* 100% width - padding + border */
        }

        input[type="submit"] {
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset</h2>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <input type="submit" value="Send Reset Link">
            <a href="login.php">Back to login</a>
        </form>
        <div class="message"></div>
    </div>
</body>
</html>
