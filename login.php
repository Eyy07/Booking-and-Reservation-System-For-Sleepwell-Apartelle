<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user = isset($_POST['username']) ? $_POST['username'] : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';

    // Debugging output
    echo "Username: $user<br>";
    echo "Password: $pass<br>";

    // Prepare SQL statement
    $sql = "SELECT * FROM login WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param('ss', $user, $pass);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful login
        header("Location: admin.html");
        exit();
    } else {
        // Failed login
        header("Location: login.html?error=invalid");
        exit();
    }

    $stmt->close();
} else {
    // If the form was not submitted, redirect to login page
    header("Location: login.html");
    exit();
}

$conn->close();
?>
