<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookings";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// SQL query to fetch bookings
$sql = "SELECT id, check_in, check_out, guests FROM bookings";
$result = $conn->query($sql);

$bookings = array();

// Check if query was successful
if ($result) {
    if ($result->num_rows > 0) {
        // Fetch all results into an array
        while($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error executing query: " . $conn->error]);
    exit();
}

// Close connection
$conn->close();

// Return the bookings as JSON
header('Content-Type: application/json');
echo json_encode($bookings);
?>
