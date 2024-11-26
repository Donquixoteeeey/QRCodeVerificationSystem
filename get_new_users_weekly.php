<?php

$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "qr_code_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as new_users_weekly FROM user_info WHERE registration_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$result = $conn->query($sql);

$new_users_weekly = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_users_weekly = $row['new_users_weekly'];
}

$conn->close();

echo $new_users_weekly;
?>
