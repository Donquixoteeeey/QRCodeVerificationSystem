<?php
// fetch_users.php
$servername = "localhost";
$username = "admin";
$password = "your_password";
$dbname = "qr_code_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details including vehicle and plate number
$sql = "SELECT id, name, vehicle, plate_number FROM user_info"; // Adjust as per your table structure
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();

echo json_encode($users); // Send user data as JSON
?>
