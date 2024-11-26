<?php
header('Content-Type: application/json');

date_default_timezone_set('Asia/Manila');

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "qr_code_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);

error_log("Incoming request data: " . json_encode($data));

if (isset($data['userId'])) {
    $userId = $conn->real_escape_string($data['userId']);
    $currentTimestamp = date('Y-m-d H:i:s'); 

    $lastActionSql = "SELECT action_type FROM user_time_logs WHERE user_id = '$userId' ORDER BY time_timestamp DESC LIMIT 1";
    $result = $conn->query($lastActionSql);
    $lastAction = $result->fetch_assoc();

    if (isset($data['timeIn'])) {
        
        if ($lastAction && $lastAction['action_type'] === 'IN') {
            echo json_encode(["status" => "error", "message" => "Already timed in. Please time out before timing in again."]);
            exit;
        }

        $sql = "INSERT INTO user_time_logs (user_id, time_timestamp, action_type) VALUES ('$userId', '$currentTimestamp', 'IN')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Time In recorded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error inserting Time In: " . $conn->error]);
        }
    } elseif (isset($data['timeOut'])) {

        if ($lastAction && $lastAction['action_type'] === 'OUT') {
            echo json_encode(["status" => "error", "message" => "Already timed out. Please time in before timing out again."]);
            exit;
        }

        $sql = "INSERT INTO user_time_logs (user_id, time_timestamp, action_type) VALUES ('$userId', '$currentTimestamp', 'OUT')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Time Out recorded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error inserting Time Out: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input. Please provide timeIn or timeOut."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User ID is required."]);
}

$conn->close();
?>
