<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost"; // your server name
$db_username = "root"; // your database username
$db_password = ""; // your database password
$dbname = "business"; // your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $email = $password = "";
$errors = [];

// Process the form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $errors[] = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $errors[] = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $errors[] = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check for errors before inserting in the database
    if (empty($errors)) {
        // Prepare a select statement to check for existing email
        $sql = "SELECT * FROM signup WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Create a password hash
            
            // Prepare an insert statement
            $sql = "INSERT INTO signup (name, email, password) VALUES (?, ?, ?)";
            $stmt->close(); // Close the previous statement
            
            // Now, insert the new user
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $password);

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                header("Location: login.html");
                exit; // Ensure no further code is executed
            } else {
                echo "<script>alert('Something went wrong. Please try again later.');</script>";
            }
        }
        // Close statement
        $stmt->close();
    }

    // If there are errors, display them
    foreach ($errors as $error) {
        echo "<script>alert('$error');</script>";
    }
}

// Close connection
$conn->close();
?>
