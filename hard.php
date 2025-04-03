<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Level</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('images/Banana.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            background: rgba(255, 248, 220, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }
        .next-button {
            background: linear-gradient(to right, #FFA500, #FF7F00);
            color: white;
            font-size: 1.5rem;
            padding: 10px 25px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
        }
        .next-button:hover {
            background: linear-gradient(to right, #FF7F00, #FF4500);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to Easy Level</h1>
    <button class="next-button" onclick="window.location.href='Game.php'">Next</button>
</div>

</body>
</html>