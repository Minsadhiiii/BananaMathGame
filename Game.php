<?php
// Set content-type to ensure proper rendering in the browser
header('Content-Type: text/html; charset=UTF-8');
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

        #score {
            font-size: 1.5rem;
            color: #333;
            font-weight: bold;
            margin-top: 20px;
        }

        #next-button {
            background-color: #28a745;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
            font-family: 'Indie Flower', cursive;
            display: none; 
        }

        #next-button:hover {
            background-color: #218838;
        }

        
        #update-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #FF5733;
            color: white;
            font-size: 18px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-family: 'Indie Flower', cursive;
            transition: background-color 0.3s;
        }

        #update-button:hover {
            background-color: #C70039;
        }
    </style>
</head>
<body>
    
    <div class="background"></div>
    <div class="blur-overlay"></div>

    <!-- Update Button -->
    <button id="update-button" onclick="window.location.href='instruction.php'">Update</button>

    <div class="game-container">
        <h1 class="title">🍌 Banana Math Challenge 🍌</h1>
        <img id="puzzle-image" src="" alt="Loading puzzle...">
        <p id="timer">30</p>
        <div id="answer-buttons"></div>
        <p id="feedback"></p>
        <p id="score">Score: 0</p> <!-- Display score -->
        <button id="next-button" onclick="fetchPuzzle()">Next Puzzle</button> <!-- Next button -->
    </div>

    <script>
        let currentSolution = null;
        let timeLeft = 30;
        let timerInterval;
        let gameOver = false;
        let currentAnswers = [];
        let score = 0;  // Initialize score variable
        let userEmail = 'nahla@gmail.com';  

        // Fetch and display the puzzle
        function fetchPuzzle() {
            if (gameOver) return;

            clearInterval(timerInterval); // Stop any previous timer
            timeLeft = 30; // Reset timer
            document.getElementById("timer").textContent = timeLeft;

            // Reset feedback and score display
            document.getElementById("feedback").textContent = '';
            document.getElementById("next-button").style.display = "none"; // 

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

    
        // Generate random answer choices including the correct solution
function generateAnswerChoices(solution) {
    let choices = [solution];
    while (choices.length < 4) {
        let randomChoice = Math.floor(Math.random() * 10); // 
        if (!choices.includes(randomChoice)) {
            choices.push(randomChoice);
        }
    }
    return choices.sort(() => Math.random() - 0.5); // 
}

        // Display answer buttons 
        function displayAnswerButtons(answers) {
            const answerButtonsContainer = document.getElementById("answer-buttons");
            answerButtonsContainer.innerHTML = ''; // Clear previous buttons

            answers.forEach(answer => {
                const button = document.createElement("button");
                button.textContent = answer;
                button.onclick = () => checkAnswer(answer, button);
                answerButtonsContainer.appendChild(button);
            });
        }

        // Start the timer
        function startTimer() {
            timerInterval = setInterval(function() {
                timeLeft--;
                document.getElementById("timer").textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    gameOver = true;
                    document.getElementById("feedback").textContent = "Time's up! Game Over!";
                    document.getElementById("next-button").style.display = "inline-block"; // Show next button
                    saveScore();  // Save the score after game over
                }
            }, 1000);
        }

        // Check if the player's answer is correct
        function checkAnswer(playerAnswer, button) {
            // Disable all buttons after answer is selected
            const buttons = document.querySelectorAll('#answer-buttons button');
            buttons.forEach(btn => btn.disabled = true);

            if (playerAnswer == currentSolution) {
                score += 10; // Increase score by 10 for correct answer
                document.getElementById("feedback").textContent = 'Correct! 🍌';
                document.getElementById("score").textContent = `Score: ${score}`; // Update score display
            } else {
                document.getElementById("feedback").textContent = 'Oops! Try again.';
            }

            // Show next button after answer is chosen
            document.getElementById("next-button").style.display = "inline-block";
        }

        // Save the score to the database using AJAX
        function saveScore() {
            const levelsPlayed = 1;  // Assuming 1 level for now

            const data = new FormData();
            data.append('email', userEmail);
            data.append('score', score);
            data.append('levelsPlayed', levelsPlayed);

            fetch('Game.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Handle the response from the PHP server
            })
            .catch(error => {
                console.error('Error saving score:', error);
            });
        }

        // Start the first puzzle when the page loads
        fetchPuzzle();
    </script>
</body>
</html>
