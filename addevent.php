<?php
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

// Process the incoming POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the event data from the POST request
    $eventName = $_POST['eventname'];
    $eventDate = $_POST['eventdate'];
    $eventLocation = $_POST['eventlocation'];
    $eventDescription = $_POST['eventdescription'];

    // Prepare an insert statement
    $sql = "INSERT INTO addevent (eventname, eventdate, eventlocation, eventdescription) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssss", $eventName, $eventDate, $eventLocation, $eventDescription);

        // Execute statement
        if ($stmt->execute()) {
            header("Location: addevent.html?status=success");
        } else {
            echo json_encode(["status" => "error", "message" => "Error adding event: " . $stmt->error]);
        }

        // Close statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare SQL statement: " . $conn->error]);
    }
}

// Close connection
$conn->close();
?>
