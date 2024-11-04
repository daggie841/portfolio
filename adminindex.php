<?php include 'adminheader.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* CSS for full-page layout */
        html, body {
            height: 1000px;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* CSS for content area with background image */
        .content {
            background-image: url('img/logo.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            flex: 1;
            padding: 20px;
        }

        /* CSS for footer */
        footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Main content goes here -->
    </div>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>
