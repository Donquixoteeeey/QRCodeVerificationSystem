<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}


$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(["error" => "Invalid JSON data received."]);
    exit;
}

$start_date = $data['start_date'];
$end_date = $data['end_date'];


if (!$start_date || !$end_date) {
    echo json_encode(["error" => "Start date or end date is missing."]);
    exit;
}

$sql = "SELECT utl.user_id, utl.time_timestamp, utl.action_type, 
               ui.name, ui.vehicle, ui.plate_number
        FROM user_time_logs AS utl
        LEFT JOIN user_info AS ui ON utl.user_id = ui.id
        WHERE DATE(utl.time_timestamp) BETWEEN ? AND ?";

$stmt = $conn->prepare($sql);


if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $start_date, $end_date);


if (!$stmt->execute()) {
    echo json_encode(["error" => "Execution failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();


$reportData = [];
while ($row = $result->fetch_assoc()) {
    $reportData[] = $row;
}

if (empty($reportData)) {
    echo json_encode(["error" => "No data found for the selected date range."]);
} else {
    echo json_encode($reportData);
}

$stmt->close();
$conn->close();
?>
