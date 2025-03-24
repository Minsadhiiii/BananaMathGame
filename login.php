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
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare SQL query to fetch user by email
    $sql = "SELECT username, email, password FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($username, $db_email, $db_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $db_password)) {
                // Store session data
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $db_email;
                
                // Redirect after login
                echo "<script>
                        alert('Login successful!');
                        window.location.href = 'instruction.html';  // Redirect to the instruction page
                      </script>";
                exit();
            } else {
                echo "<script>alert('Incorrect password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No user found with this email. Please register.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>
