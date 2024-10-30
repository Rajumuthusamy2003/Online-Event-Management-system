<?php
// Database connection settings
$servername = "localhost"; 
$username = "root"; 
$pass = ""; 
$dbname = "business"; 

// Create connection
$conn = new mysqli($servername, $username, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$user_input = $password_input = "";
$errors = [];

// Process the form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username/email
    if (empty(trim($_POST["email"]))) {
        $errors[] = "Please enter your username or email.";
        header("Location: visi.php");
    } else {
        $user_input = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $errors[] = "Please enter your password.";
    } else {
        $password_input = trim($_POST["password"]);
    }

    // Check for errors before querying the database
    if (empty($errors)) {
        // Prepare a select statement
        $sql = "SELECT email, password FROM signup WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $user_input); // Bind the email parameter
            $stmt->execute(); // Execute the statement
            $stmt->store_result(); // Store the result

            // Check if username/email exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($email, $stored_password); // Bind email and stored password
                $stmt->fetch(); // Fetch the result

                // Check if the entered password matches the stored password
                if ($password_input === $stored_password) {
                    // Password is correct, redirect to visi.php
                    header("Location: visi.php");
                    exit; // Exit after redirection to avoid further processing
                } else {
                    $errors[] = "Invalid password. Please try again.";
                }
            } else {
                $errors[] = "No account found with that username/email.";
            }

            // Close statement
            $stmt->close();
        } else {
            // Handle prepare statement failure
            $errors[] = "Failed to prepare SQL statement.";
        }
    }
}

// Close connection
$conn->close();

// Display errors if any
if (!empty($errors)) {
    echo "<script>alert('" . implode("\\n", $errors) . "'); window.history.back();</script>";
}
?>
