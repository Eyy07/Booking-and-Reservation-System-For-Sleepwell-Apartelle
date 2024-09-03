<?php
$test_user = 'yourusername';
$test_pass = 'yourpassword';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param('s', $test_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "Stored Password Hash: " . $row['password'] . "<br>";

    if (password_verify($test_pass, $row['password'])) {
        echo "Password verified.";
    } else {
        echo "Password not verified.";
    }
} else {
    echo "No user found with the provided username.";
}

$stmt->close();
$conn->close();
?>
