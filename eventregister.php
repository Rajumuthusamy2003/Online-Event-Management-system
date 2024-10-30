<?php
session_start(); // Start the session to access session variables

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost"; 
$username = "root"; 
$password = ""; // Your database password
$dbname = "business"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $event = $_POST['event'];

    // Prepare SQL query to insert data
    $sql = "INSERT INTO eventregister (name, email, event) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $event);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Thank you for registering for the event!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Retrieve available events from the session
$events = isset($_SESSION['events']) ? $_SESSION['events'] : [];

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            margin-top: 10px;
            text-align: left;
        }
        form input, form button, form select {
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
        .message {
            text-align: center;
            font-size: 16px;
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="eventregister.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="event">Select Event:</label>
        <select id="event" name="event" required>
            <option value="">--Select Event--</option>
            <?php foreach ($events as $eventName): ?>
                <option value="<?php echo htmlspecialchars($eventName); ?>"><?php echo htmlspecialchars($eventName); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Register</button>
    </form>
</body>
</html>
