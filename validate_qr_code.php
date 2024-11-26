<?php

$input = json_decode(file_get_contents("php://input"), true);
$qr_code_text = isset($input['qr_code_text']) ? $input['qr_code_text'] : '';


$pattern = '/Name:\s*(.*?)\s*\|\s*Vehicle:\s*(.*?)\s*\|\s*Plate Number:\s*(.*)/';
if (preg_match($pattern, $qr_code_text, $matches)) {
    $name = trim($matches[1]);
    $vehicle = trim($matches[2]);
    $plate_number = trim($matches[3]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid QR code format']);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed']));
}

$sql = "SELECT id, name, vehicle, plate_number FROM user_info WHERE name = ? AND vehicle = ? AND plate_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $name, $vehicle, $plate_number);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();


if ($user) {
    
    echo json_encode(['success' => true, 'user' => $user]);
} else {
   
    echo json_encode(['success' => false, 'message' => 'User not found or invalid QR code']);
}
?>
