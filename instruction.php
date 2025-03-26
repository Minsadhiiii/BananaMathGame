<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banana_game";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email']; // Get email from session

// Fetch user details from the database
$sql = "SELECT username, profile_pic FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($username, $profile_pic);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banana Game - Instructions</title>
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

        /* Full-Screen Background with Blurred Overlay */
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

        /* Exit Button */
        .exit-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: red;
            color: white;
            font-size: 1.2em;
            border: none;
            padding: 10px 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Indie Flower', cursive;
        }

        .exit-button:hover {
            background: darkred;
            transform: scale(1.1);
        }

        /* Main container */
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

        h2 {
            color: #333;
            font-size: 2em;
        }

        .instructions {
            font-size: 1.3em;
            color: #444;
            text-align: left;
            line-height: 1.6;
            margin-top: 15px;
        }

        .banana-icon {
            font-size: 2em;
            color: #FFA500;
            margin-right: 10px;
        }

        /* Button Styles */
        .button {
            background: linear-gradient(to right, #FFA500, #FF7F00);
            color: white;
            font-size: 1.3em;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Indie Flower', cursive;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .button:hover {
            background: linear-gradient(to right, #FF7F00, #FF4500);
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="background"></div>
    <div class="blur-overlay"></div>

    <button class="exit-button" onclick="window.location.href='main.html'">Exit</button>

    <div class="container">
        <h2><i class="fas fa-info-circle banana-icon"></i>Game Instructions</h2>
        <div class="instructions">
            <p><i class="fas fa-check-circle banana-icon"></i> Select a level to start the game.</p>
            <p><i class="fas fa-check-circle banana-icon"></i> Solve the math problems quickly to earn points.</p>
            <p><i class="fas fa-check-circle banana-icon"></i> Each correct answer gives you bananas as rewards!</p>
            <p><i class="fas fa-check-circle banana-icon"></i> Reach the score goal to unlock the next level.</p>
            <p><i class="fas fa-check-circle banana-icon"></i> Have fun and challenge yourself!</p>
        </div>

        <button class="button" onclick="window.location.href='select.html'">Play</button>
        <button class="button" onclick="window.location.href='scoreboard.html'">Go to Scoreboard</button>
        <button class="button" onclick="window.location.href='profile.php'">View Profile</button>
    </div>
</body>
</html>
