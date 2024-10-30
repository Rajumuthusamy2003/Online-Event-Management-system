<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "business"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variable to hold the status message
$statusMessage = "";

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare SQL query to insert data
    $sql = "INSERT INTO contactus (name, email, message) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        $statusMessage = "Thank you for your support!";
    } else {
        $statusMessage = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body, html {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            margin-top: 10px;
            text-align: left;
        }
        form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 15px;
        }
        form button:hover {
            background-color: #555;
        }
        .status-message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
            color: green;
        }
        .error-message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Contact Us</h2>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter yor Name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" placeholder="Comments your Queries" required></textarea>

        <button type="submit">Send Message</button>
    </form>

    <?php
    // Display the status message
    if (!empty($statusMessage)) {
        echo "<div class='status-message'>{$statusMessage}</div>";
    }
    ?>
</body>
</html>
