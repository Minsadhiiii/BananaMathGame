<?php
session_start(); // Start the session to track user login status

// Check if the user is logged in
if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit(); // Stop further execution
}

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banana_game";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Terminate script if connection fails
}

$email = $_SESSION['email']; // Retrieve the email from session data

// Fetch user details (username and profile picture) from the users table
$sql = "SELECT username, profile_pic FROM users WHERE email = ?";
$stmt = $conn->prepare($sql); // Prepare the SQL statement to prevent SQL injection
$stmt->bind_param("s", $email); // Bind the email parameter to the statement
$stmt->execute(); // Execute the query
$stmt->bind_result($username, $profile_pic); // Bind the result variables
$stmt->fetch(); // Fetch the values
$stmt->close(); // Close the prepared statement

// Fetch user's score and levels played from the scores table based on their email
$sql_score = "SELECT score, levels_played FROM scores WHERE user_id = (SELECT user_id FROM users WHERE email = ?)";
$stmt_score = $conn->prepare($sql_score); // Prepare the SQL statement
$stmt_score->bind_param("s", $email); // Bind the email parameter
$stmt_score->execute(); // Execute the query
$stmt_score->bind_result($score, $levels_played); // Bind the result variables
$stmt_score->fetch(); // Fetch the values
$stmt_score->close(); // Close the prepared statement

// Close the database connection after retrieving the necessary data
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
        /* Style for the entire body to center content */
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

        /* Background image */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/Banana.jpg') no-repeat center center/cover;
            z-index: -2;
        }

        /* Blurred overlay on top of the background */
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

        /* Exit button (redirects user to main page) */
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

        /* Hover effect for exit button */
        .exit-button:hover {
            background: darkred;
            transform: scale(1.1);
        }

        /* Profile container */
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

        /* Heading style */
        h2 {
            color: #333;
            font-size: 2em;
        }

        /* Style for user information */
        .profile-info {
            font-size: 1.3em;
            color: #444;
            margin-top: 15px;
        }

        /* Banana icon style */
        .banana-icon {
            font-size: 2em;
            color: #FFA500;
            margin-right: 10px;
        }

        /* Profile picture styling */
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        /* Button styling */
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

        /* Hover effect for buttons */
        .button:hover {
            background: linear-gradient(to right, #FF7F00, #FF4500);
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <!-- Background and blur overlay for the profile page -->
    <div class="background"></div>
    <div class="blur-overlay"></div>
    
    <!-- Exit button to go back to the main page -->
    <button class="exit-button" onclick="window.location.href='main.html'">Exit</button>

    <!-- Profile information container -->
    <div class="container">
        <h2><i class="fas fa-user-circle banana-icon"></i>User Profile</h2>
        
        <!-- Display user profile information -->
        <div class="profile-info">
            <img src="images/Profile pic.jpg" alt="Profile Picture" class="profile-pic"> <!-- Profile picture -->
            <p><strong>Username:</strong> <?php echo $username; ?></p> <!-- Display username -->
            <p><strong>Email:</strong> <?php echo $email; ?></p> <!-- Display email -->
            <p><strong>Final Score:</strong> <?php echo $score; ?></p> <!-- Display final score -->
            <p><strong>Levels Played:</strong> <?php echo $levels_played; ?></p> <!-- Display levels played -->
        </div>

        <!-- Navigation buttons -->
        <button class="button" onclick="window.location.href='instruction.php'">Back to Instructions</button>
        <button class="button" onclick="window.location.href='scoreboard.php'">Go to Scoreboard</button>
    </div>
</body>

</html>
