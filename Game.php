<?php
session_start();
include 'db_connect.php'; // Include your database connection file

if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$email = $_SESSION['email'];

// Fetch user details
$query = $conn->prepare("SELECT id, user_id, score, levels_played FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found!");
}

$user_id = $user['user_id'];
$score = $user['score'];
$levels_played = $user['levels_played'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correct'])) {
    // Update score and levels played
    $newScore = $score + 10;
    $newLevels = $levels_played + 1;
    
    $updateQuery = $conn->prepare("UPDATE users SET score = ?, levels_played = ?, date_played = NOW() WHERE user_id = ?");
    $updateQuery->bind_param("iii", $newScore, $newLevels, $user_id);
    $updateQuery->execute();

    // Update the session values
    $_SESSION['score'] = $newScore;
    $_SESSION['levels_played'] = $newLevels;
    
    echo json_encode(["success" => true, "newScore" => $newScore, "newLevels" => $newLevels]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banana Math Game</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap');

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

        .game-container {
            background: rgba(255, 248, 220, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 90%;
            width: 400px;
            position: relative;
            z-index: 1;
        }

        .title {
            color: #FFCC00;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        #puzzle-image {
            max-width: 100%;
            margin-bottom: 10px;
        }

        #timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        #answer-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        #answer-buttons button {
            background-color: #FFA500;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
            font-family: 'Indie Flower', cursive;
        }

        #answer-buttons button:hover {
            background-color: #FF7F00;
        }

        #feedback {
            font-size: 1.2rem;
            margin-top: 10px;
            color: #333;
        }

        #score-display {
            font-size: 1.5rem;
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    
    <div class="background"></div>
    <div class="blur-overlay"></div>

    <div class="game-container">
        <h1 class="title">🍌 Banana Math Challenge 🍌</h1>
        <p><strong>User:</strong> <?= htmlspecialchars($email) ?></p>
        <p id="score-display">Score: <?= $score ?></p>
        <img id="puzzle-image" src="" alt="Loading puzzle...">
        <p id="timer">30</p>
        <div id="answer-buttons"></div>
        <p id="feedback"></p>
    </div>

    <script>
        let currentSolution = null;
        let timeLeft = 30;
        let timerInterval;
        let gameOver = false;
        let currentAnswers = [];

        function fetchPuzzle() {
            if (gameOver) return;

            clearInterval(timerInterval);
            timeLeft = 30;
            document.getElementById("timer").textContent = timeLeft;

            fetch("https://marcconrad.com/uob/banana/api.php")
                .then(response => response.json())
                .then(data => {
                    document.getElementById("puzzle-image").src = data.question;
                    currentSolution = data.solution;
                    currentAnswers = generateAnswerChoices(currentSolution);
                    displayAnswerButtons(currentAnswers);
                    startTimer();
                })
                .catch(error => {
                    document.getElementById("feedback").textContent = 'Error loading puzzle. Please try again later.';
                    console.error("Error fetching puzzle:", error);
                });
        }

        function generateAnswerChoices(solution) {
            let choices = [solution];
            while (choices.length < 4) {
                let randomChoice = Math.floor(Math.random() * 100);
                if (!choices.includes(randomChoice)) {
                    choices.push(randomChoice);
                }
            }
            return choices.sort(() => Math.random() - 0.5);
        }

        function displayAnswerButtons(answers) {
            const answerButtonsContainer = document.getElementById("answer-buttons");
            answerButtonsContainer.innerHTML = ''; 

            answers.forEach(answer => {
                const button = document.createElement("button");
                button.textContent = answer;
                button.onclick = () => checkAnswer(answer);
                answerButtonsContainer.appendChild(button);
            });
        }

        function startTimer() {
            timerInterval = setInterval(function() {
                timeLeft--;
                document.getElementById("timer").textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    gameOver = true;
                    document.getElementById("feedback").textContent = "Time's up! Game Over!";
                }
            }, 1000);
        }

        function checkAnswer(playerAnswer) {
            if (playerAnswer == currentSolution) {
                document.getElementById("feedback").textContent = 'Correct! 🍌';
                
                fetch("", { method: "POST", body: new URLSearchParams({ correct: true }) })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("score-display").textContent = "Score: " + data.newScore;
                        fetchPuzzle();
                    });

            } else {
                document.getElementById("feedback").textContent = 'Oops! Try again.';
            }
        }

        fetchPuzzle();
    </script>
</body>
</html>
