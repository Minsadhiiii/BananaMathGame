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
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if passwords match
    if ($password === $confirm_password) {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert user data
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters and execute the statement
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            // Execute query and check if insertion was successful
            if ($stmt->execute()) {
                echo "Registration successful! You can now <a href='login.html'>login</a>.";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Passwords do not match. Please try again.";
    }
}

// Close the connection after use
$conn->close();
?>
