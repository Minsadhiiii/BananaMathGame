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

    // Prepare SQL query to fetch user details
    $sql = "SELECT username, email, password FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) { // Prepare the SQL statement to prevent SQL injection
        // Bind parameters and execute query
        $stmt->bind_param("s", $email); // Bind email parameter to the SQL statement
        $stmt->execute(); // Execute the prepared statement
        $stmt->store_result(); // Store the result of the query

        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($username, $db_email, $db_password); // Bind fetched columns to variables
            $stmt->fetch(); // Fetch the user details

            // Verify password
            if (password_verify($password, $db_password)) { // Compare the provided password with the hashed password
                // Store user details in session
                $_SESSION['username'] = $username; // Store username in session
                $_SESSION['email'] = $db_email; // Store email in session

                // Alert and redirect to instruction.php
                echo "<script>
                        alert('Login successful! Welcome, $username');
                        window.location.href = 'instruction.php';  // Redirect to instruction page 
                      </script>";
                exit();
            } else {
                echo "<script>alert('Incorrect password. Please try again.');</script>"; // Display incorrect password alert
            }
        } else {
            echo "<script>alert('No user found with this email. Please register.');</script>"; // Alert for unregistered email
        }
        $stmt->close(); // Close statement
    } else {
        // If query preparation fails, output the error
        echo "<script>alert('SQL Query Preparation failed: " . $conn->error . "');</script>"; // Alert if SQL statement preparation fails
    }
}

$conn->close(); // Close database connection
?>
