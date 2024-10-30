<?php
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
// Query to fetch events
$sql = "SELECT eventname, eventdate, eventlocation, eventdescription FROM addevent";
$result = $conn->query($sql);
// Fetch events
$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
        #event-list {
            margin-top: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .event-item {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
        }
        .event-item strong {
            display: block;
            margin-bottom: 5px;
        }
        .register-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: green;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Manage Events</h1>
    <div id="event-list">
        <?php if (empty($events)): ?>
            <p>No events available.</p>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="event-item">
                    <strong><?php echo htmlspecialchars($event['eventname']); ?></strong>
                    <p>Date: <?php echo htmlspecialchars($event['eventdate']); ?></p>
                    <p>Location: <?php echo htmlspecialchars($event['eventlocation']); ?></p>
                    <p>Description: <?php echo htmlspecialchars($event['eventdescription']); ?></p>
                    <a href="eventregister.php" class="register-button">Register</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="back-link">
        <a href="visi.php"><h4 style="color: brown;">Do you want to Organize any event?</h4></a>
    </div>
</body>
</html>
