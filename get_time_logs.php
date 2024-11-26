<?php

$servername = "localhost";
$username = "admin";
$password = "your_password";
$dbname = "qr_code_management";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT user_id, time_in, time_out FROM user_time_logs");
$timeLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($timeLogs);
?>
