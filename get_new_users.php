<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "qr_code_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, vehicle, registration_date FROM user_info ORDER BY registration_date DESC LIMIT 5";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row; 
    }
}

header('Content-Type: application/json');
echo json_encode($users);

$conn->close();
?>
