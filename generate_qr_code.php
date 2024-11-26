<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila'); 

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

if ($user_id <= 0) {
    echo "<script>
        alert('Invalid user ID');
        window.history.back();
    </script>";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, vehicle, plate_number FROM user_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    $user_name = $user['name'];
    $vehicle = $user['vehicle'];
    $plate_number = $user['plate_number'];

    $qr_code_data = 'Name: ' . urlencode($user_name) . ' | Vehicle: ' . urlencode($vehicle) . ' | Plate Number: ' . urlencode($plate_number);
    $qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $qr_code_data;

    $expiration_date = date('Y-m-d H:i:s', strtotime('+1 year')); 
    $generated_at = date('Y-m-d H:i:s'); 
   
    $update_sql = "UPDATE user_info SET qr_code_url = ?, expiration_date = ?, qr_code_generated_at = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sssi', $qr_code_url, $expiration_date, $generated_at, $user_id);
    
    if ($update_stmt->execute()) {
        echo "QR Code URL: <a href='" . htmlspecialchars($qr_code_url) . "' target='_blank'>" . htmlspecialchars($qr_code_url) . "</a><br>";
        echo "Expiration Date: " . htmlspecialchars($expiration_date) . "<br>";
        echo "QR Code Generated At: " . htmlspecialchars($generated_at); 

        // Redirect after successful update
        header('Location: qr_code_management.php?qr_code_url=' . urlencode($qr_code_url));
        exit;
    } else {
        die('Error updating QR code data: ' . $update_stmt->error);
    }
} else {
    echo "<script>
        alert('User not found');
        window.history.back();
    </script>";
    exit;
}

$conn->close();
?>
