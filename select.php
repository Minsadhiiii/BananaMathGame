<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banana Math Game</title>
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <style> 
        /* Full-Screen Background */
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

        /* Background Image */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/Banana.jpg') no-repeat center center/cover;
            z-index: -2;
        }

        /* Blurred Overlay */
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

        /* Back Arrow */
        .back-arrow {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 2.5rem;
            color: #007bff;
            cursor: pointer;
            transition: 0.3s;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            background: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .back-arrow:hover {
            color: white;
            background: #007bff;
            transform: scale(1.2);
            text-shadow: none;
        }

        /* Game Container */
        .container {
            text-align: center;
            background: rgba(255, 248, 220, 0.9);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .title {
            font-size: 3.5rem;
            color: #5C4033;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 30px;
        }

        /* Button Container */
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        /* Play Button */
        .play-button {
            background: linear-gradient(to right, #FFA500, #FF7F00);
            color: white;
            font-size: 1.6rem;
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            font-family: 'Indie Flower', cursive;
            width: 200px;
        }

        .play-button:hover {
            background: linear-gradient(to right, #FF7F00, #FF4500);
            transform: scale(1.05);
        }  
    </style>
</head>
<body>
    <!-- Background Image -->
    <div class="background"></div>
    <!-- Blurred Overlay -->
    <div class="blur-overlay"></div>
    <!-- Back Arrow -->
    <div class="back-arrow" onclick="goBack()">⬅</div> <!-- The function goBack() is called when the back arrow is clicked -->
    <!-- Game Container -->
    <div class="container">
        <h1 class="title">The BANANA GAME!</h1>
        <p class="subtitle">"Test your math skills with a fun banana challenge"</p>
        <!-- Difficulty Buttons -->
        <div class="button-container">
            <button class="play-button" id="easyBtn">EASY</button>
            <button class="play-button" id="mediumBtn">MEDIUM</button>
            <button class="play-button" id="hardBtn">HARD</button>
        </div>
    </div>
    <script>
        // Function to navigate to the instruction page
        function goBack() {
            window.location.href = "Instruction.php"; // Redirects to Instruction.php when back arrow is clicked
        }

        // Function to start the game based on difficulty
        function startGame(difficulty) {
            const pages = {
                'easy': 'easylevel.php',
                'medium': 'medium.php',
                'hard': 'hard.php'
            };

            // Store the selected difficulty in session
            fetch('setDifficulty.php', {
                method: 'POST',
                body: new URLSearchParams({
                    difficulty: difficulty
                })
            });

            if (pages[difficulty]) {
                window.location.href = pages[difficulty]; // Redirects to the appropriate level based on selected difficulty
            } else {
                alert("Invalid difficulty level."); // Alerts if the difficulty level is invalid
            }
        }

        // Event listeners for buttons
        document.getElementById("easyBtn").addEventListener("click", function() {
            startGame('easy'); // Starts the game with easy difficulty when easy button is clicked
        });

        document.getElementById("mediumBtn").addEventListener("click", function() {
            startGame('medium'); // Starts the game with medium difficulty when medium button is clicked
        });

        document.getElementById("hardBtn").addEventListener("click", function() {
            startGame('hard'); // Starts the game with hard difficulty when hard button is clicked
        });
    </script>
</body>
</html>
