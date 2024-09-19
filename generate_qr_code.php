<?php
// Get user ID from POST request
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

if ($user_id <= 0) {
    die('Invalid user ID');
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details, vehicle, and plate number
$sql = "SELECT name, vehicle, plate_number FROM user_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($user) {
    $user_name = $user['name'];
    $vehicle = $user['vehicle'];
    $plate_number = $user['plate_number'];
} else {
    die('User not found');
}

// Generate QR code URL with name, vehicle, and plate number
$qr_code_data = 'Name: ' . urlencode($user_name) . ' | Vehicle: ' . urlencode($vehicle) . ' | Plate Number: ' . urlencode($plate_number);
$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $qr_code_data;

// Debug output
echo "QR Code URL: " . htmlspecialchars($qr_code_url);

// Redirect to display QR code
header('Location: qr_code_management.php?qr_code_url=' . urlencode($qr_code_url));
exit;
?>
