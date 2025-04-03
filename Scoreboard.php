<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banana_game";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch player scores from the database (sorted by rank)
$sql = "SELECT user_id, score, rank, levels_played FROM scores ORDER BY rank ASC";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $players = [];
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }
} else {
    $players = [];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoreboard - Banana Math Game</title>
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
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

        
        .score-table {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
        }

        .score-table th, .score-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .score-table th {
            background: #FFA500;
            color: white;
            font-size: 1.2rem;
        }

        .score-table tr:nth-child(even) {
            background: #fffbe6;
        }

        .score-table tr:nth-child(odd) {
            background: #ffefc1;
        }

        .score-table tr:hover {
            background: #ffdb85;
            transition: 0.3s;
        }

        
        .back-button {
            margin-top: 20px;
            padding: 12px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            background: #FFA500;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Indie Flower', cursive; 
        }

        .back-button:hover {
            background: #ff8c00;
        }
    </style>
</head>
<body>

    
    <div class="background"></div>

    
    <div class="blur-overlay"></div>

    
    <div class="container">
        <h1 class="title">Game Scoreboard</h1>
        <p class="subtitle">Track your progress and compare with others!</p>

        <!-- Score Table -->
        <table class="score-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>User ID</th>
                    <th>Levels Played</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody id="scoreboard-body">
                <!-- Scoreboard will be dynamically updated here -->
                <?php 
                //  display player scores
                foreach ($players as $index => $player) {
                    echo "<tr>
                        <td>" . $player['rank'] . "</td>
                        <td>" . $player['user_id'] . "</td>
                        <td>" . $player['levels_played'] . "</td>
                        <td>" . $player['score'] . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        
        <button class="back-button" onclick="goBack()">Back</button>
    </div>

    <script>
        
        function goBack() {
            window.location.href = "Instruction.php"; // Ensure this file exists
        }
    </script>

</body>
</html>
