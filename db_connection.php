<?php

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "data_bank";     

try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT ID, FIRST_NAME, LAST_NAME FROM accounts";
    
    $stmt = $conn->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>User Data Table</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th></tr>";
    
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
        echo "<td>" . htmlspecialchars($row['FIRST_NAME']) . "</td>";
        echo "<td>" . htmlspecialchars($row['LAST_NAME']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    $conn = null;
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
