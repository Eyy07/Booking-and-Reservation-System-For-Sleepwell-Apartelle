<?php
$servername = "localhost";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "user_database"; // This is your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize form inputs
$user_username = mysqli_real_escape_string($conn, $_POST['username']);
$user_email = mysqli_real_escape_string($conn, $_POST['email']);
$user_password = mysqli_real_escape_string($conn, $_POST['password']);

// Hash the password before saving
$hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

// Prepare SQL statement for the table
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param('sss', $user_username, $user_email, $hashed_password);

if ($stmt->execute()) {
    echo "New record created successfully";
    // Redirect to a login page or another page
    header("Location: login.html");
    exit(); // Make sure to call exit after redirect to prevent further script execution
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
