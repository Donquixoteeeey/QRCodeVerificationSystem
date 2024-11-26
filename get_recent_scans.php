<?php

$conn = new mysqli("localhost", "root", "", "qr_code_management");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_id, time_timestamp, action_type FROM user_time_logs ORDER BY time_timestamp DESC LIMIT 5";
$result = $conn->query($sql);

if ($result === false) {
    die("SQL Error: " . $conn->error);
}

$scans = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scans[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($scans);

$conn->close();
?>
