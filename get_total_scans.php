<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "qr_code_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as total_scans FROM user_time_logs WHERE DATE(time_timestamp) = CURDATE()";
$result = $conn->query($sql);

$total_scans = 0; 

if ($result->num_rows > 0) {
    
    $row = $result->fetch_assoc();
    $total_scans = $row['total_scans'];
}

echo json_encode(['total_scans_today' => $total_scans]);

$conn->close();
?>
