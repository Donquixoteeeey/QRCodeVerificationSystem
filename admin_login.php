<?php
session_start();
header('Content-Type: application/json');

$host = 'localhost';
$db = 'qr_code_management';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? null;
    $password = $data['password'] ?? null;

    
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = null;
    }

   
    if ($_SESSION['lockout_time'] && time() < $_SESSION['lockout_time']) {
        $remaining_time = $_SESSION['lockout_time'] - time();
        echo json_encode([
            'success' => false,
            'message' => "Too many attempts. Please wait {$remaining_time} seconds."
        ]);
        exit;
    }

    $query = "SELECT username, password FROM admins WHERE BINARY username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

       
        if ($password === $row['password']) {
            $_SESSION['loggedin'] = true;

            
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;

            $redirectUrl = ($username === 'admin2') ? 'phone_activity_logs.php' : 'dashboard.php';
            echo json_encode(['success' => true, 'redirect' => $redirectUrl]);
        } else {
            $_SESSION['login_attempts']++;

            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lockout_time'] = time() + 60; 
                echo json_encode([
                    'success' => false,
                    'message' => 'Too many attempts. Locked out for 1 minute.'
                ]);
            } else {
                $remaining_attempts = 3 - $_SESSION['login_attempts'];
                echo json_encode([
                    'success' => false,
                    'message' => "Invalid password. {$remaining_attempts} attempts remaining."
                ]);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username not found']);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
