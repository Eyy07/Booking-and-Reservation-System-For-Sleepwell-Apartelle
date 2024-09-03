<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume we get the user ID from session or request
// For example purposes, using a fixed user ID
$user_id = 1; // Replace with dynamic user ID from session or request

// Fetch user data
$sql = "SELECT username, email, fullname FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo json_encode(["error" => "No user found"]);
}

// Close connection
$stmt->close();
$conn->close();
?>
