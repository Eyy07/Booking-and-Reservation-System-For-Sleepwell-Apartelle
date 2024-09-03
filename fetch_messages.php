<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "messages";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, email, message FROM messages";
$result = $conn->query($sql);

$messages = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
} else {
    echo json_encode(array());
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($messages);
?>
