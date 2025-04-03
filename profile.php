<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banana_game";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email']; // Get email from session

// Fetch user details and score from the database
$sql = "SELECT u.username, u.profile_pic, s.score, s.levels_played 
        FROM users u 
        JOIN scores s ON u.id = s.user_id  
        WHERE u.email = ?";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();

// Check if the query executed successfully
if ($stmt->error) {
    die('Execute error: ' . $stmt->error);
}

$stmt->bind_result($username, $profile_pic, $score, $levels_played);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banana Game - Profile</title>
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

        .profile-info {
            font-size: 1.3em;
            color: #444;
            margin-top: 15px;
        }

        .banana-icon {
            font-size: 2em;
            color: #FFA500;
            margin-right: 10px;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

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
        <h2><i class="fas fa-user-circle banana-icon"></i>User Profile</h2>
        <div class="profile-info">
            <img src="profile_pics/<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic">
            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Score:</strong> <?php echo $score; ?></p>
            <p><strong>Levels Played:</strong> <?php echo $levels_played; ?></p>
        </div>

        <button class="button" onclick="window.location.href='instruction.php'">Back to Instructions</button>
        <button class="button" onclick="window.location.href='scoreboard.html'">Go to Scoreboard</button>
    </div>
</body>

</html>
