<?php
// Start or resume the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // User is already signed in, display the "Congratulations" message
    header('Location: congratulations.html'); // Redirect to a page with the message
    exit; // Stop further execution
}

// Replace these with your actual database credentials
$hostname = 'localhost';
$username = 'jeff'; // Updated username
$password = 'ladignon'; // Updated password
$database = 'form';

// Create a database connection
$connection = new mysqli($hostname, $username, $password, $database);

// Check for connection errors
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_POST['login_btn'])) {
    $login_email = $_POST['login_email'];
    $login_password = $_POST['login_password'];

    // Query the database to check if the user exists
    $sql = "SELECT * FROM information WHERE email = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt === false) {
        die("Error in prepared statement: " . $connection->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("s", $login_email);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User exists, check the password
        $row = $result->fetch_assoc();
        if (password_verify($login_password, $row['password'])) {
            // Password is correct, set the user session
            $_SESSION['user_id'] = $row['id'];

            // Redirect to a welcome page or user dashboard
            header('Location: welcome.html');
            exit; // Stop further execution
        } else {
            // Incorrect password, display an error message
            echo "Incorrect password.";
        }
    } else {
        // User doesn't exist, display an error message
        echo "User not found.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$connection->close();
?>
