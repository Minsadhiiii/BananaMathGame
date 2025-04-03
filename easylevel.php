<?php
session_start(); // Starts the session to manage user sessions

// Check if the user is logged in
// If the user is not logged in, they are redirected to the login page.
if (!isset($_SESSION['email'])) {
    header("Location: login.html"); // Redirects to the login page if not logged in
    exit(); // Exits the script to prevent further execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Level</title>
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Indie Flower', cursive;
            position: relative;
            overflow: hidden;
        }

        /* Background and Blur Overlay */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/Banana.jpg') no-repeat center center/cover;
            z-index: -2;
        }

        .blur-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 235, 133, 0.4);
            backdrop-filter: blur(8px);
            z-index: -1;
        }

        /* Container Styling */
        .container {
            background: rgba(255, 248, 220, 0.8);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 500px;
            position: relative;
            z-index: 1;
        }

        h1 {
            font-size: 2.5em;
            color: #333;
        }

        /* Button Styling */
        .next-button {
            background: linear-gradient(to right, #FFA500, #FF7F00);
            color: white;
            font-size: 1.5rem;
            padding: 12px 30px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Indie Flower', cursive;
            margin-top: 15px;
        }

        .next-button:hover {
            background: linear-gradient(to right, #FF7F00, #FF4500);
            transform: scale(1.1);
        }
    </style>
</head>
<body>


<div class="background"></div>
<div class="blur-overlay"></div>

<!-- Main Container -->
<div class="container">
    <h1>Welcome to Easy Level</h1>
    <!-- The button triggers a page redirection when clicked -->
    <button class="next-button" onclick="window.location.href='Game.php'">Next</button> <!-- Handles the button click event to redirect to Game.php -->
</div>

</body>
</html>
