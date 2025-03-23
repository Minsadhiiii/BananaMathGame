<?php
// Database credentials
$servername = "localhost"; // MySQL server
$username = "root";        // Default MySQL username in XAMPP
$password = "";            // Default MySQL password is empty in XAMPP
$dbname = "banana_game";   // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Connection failed, display error message
    die("Connection failed: " . $conn->connect_error);
} else {
    // Connection successful
    echo "Connected successfully to the database: $dbname";
}

// Close the connection after use
$conn->close();
?>