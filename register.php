<?php
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

if (isset($_POST['register_btn'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation (you can add more validation here)

    // Hash the password (for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement to insert user data into the "information" table
    $sql = "INSERT INTO information (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

    // Create a prepared statement
    $stmt = $connection->prepare($sql);

    if ($stmt === false) {
        die("Error in prepared statement: " . $connection->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_password);

    if ($stmt->execute()) {
        // Redirect to login page after successful registration
        header('Location: index.html');
    } else {
        // Handle registration error
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$connection->close();
?>
