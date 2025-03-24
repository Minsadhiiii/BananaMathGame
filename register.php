<?php
session_start(); // Start session

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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if passwords match
    if ($password != $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to check if user already exists
        $sql = "SELECT email FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Check if email already exists
            if ($stmt->num_rows > 0) {
                echo "<script>alert('Email already exists. Please use a different email.');</script>";
            } else {
                // Prepare SQL query to insert new user
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sss", $username, $email, $hashed_password);
                    
                    // Execute query and check for success
                    if ($stmt->execute()) {
                        // Registration successful
                        echo "<script>alert('Registration successful! Welcome to The Banana Game.');</script>";
                        echo "<script>window.location.href = 'login.html';</script>";
                    } else {
                        // If query execution fails, show the error
                        echo "<script>alert('Error: Could not register user. Error: " . $stmt->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
                }
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
        }
    }
}

$conn->close();
?>