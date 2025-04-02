<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banana Math Game - Levels</title>
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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

        /* Game Container */
        .game-container {
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

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        /* Play Button */
        .level-button {
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

        .level-button:hover {
            background: linear-gradient(to right, #FF7F00, #FF4500);
            transform: scale(1.05);
        }

        /* Timer and Score */
        #timer,
        #score-display {
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
        <p>Welcome! Choose your difficulty level to start playing!</p>

        <div id="level-buttons">
            <button class="level-button" id="easy-level">Easy</button>
            <button class="level-button" id="medium-level">Medium</button>
            <button class="level-button" id="hard-level">Hard</button>
        </div>

        <div id="game-container" style="display: none;">
            <p id="score-display">Score: 0</p>
            <img id="puzzle-image" src="" alt="Loading puzzle...">
            <p id="timer">30</p>
            <div id="answer-buttons"></div>
            <p id="feedback"></p>
        </div>
    </div>

    <script>
        let currentSolution = null;
        let timeLeft = 30;
        let timerInterval;
        let gameOver = false;
        let currentAnswers = [];
        let score = 0;
        let level = 'easy'; // Default level

        document.getElementById("easy-level").addEventListener("click", () => startGame('easy'));
        document.getElementById("medium-level").addEventListener("click", () => startGame('medium'));
        document.getElementById("hard-level").addEventListener("click", () => startGame('hard'));

        function startGame(selectedLevel) {
            level = selectedLevel;
            document.getElementById("level-buttons").style.display = "none";
            document.getElementById("game-container").style.display = "block";
            fetchPuzzle();
        }

        function fetchPuzzle() {
            if (gameOver) return;

            clearInterval(timerInterval);
            timeLeft = 30;
            document.getElementById("timer").textContent = timeLeft;

            fetch("https://marcconrad.com/uob/banana/api.php?level=" + level)
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
            timerInterval = setInterval(function () {
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
                score += 10; // Increase score for correct answer
                document.getElementById("score-display").textContent = "Score: " + score;
                fetchPuzzle(); // Fetch the next puzzle
            } else {
                document.getElementById("feedback").textContent = 'Oops! Try again.';
            }
        }
    </script>

</body>

</html>