<?php
include 'db_connect.php';

$userId = $_GET['id'] ?? '';

if ($userId) {
    $sql = "SELECT id, name, vehicle, plate_number, contact_number FROM user_info WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            die('User not found');
        }
    } else {
        die('Database query failed');
    }
} else {
    die('No user ID provided');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-family: 'Comfortaa', cursive;
            color: #2a3d6b;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            margin-bottom: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .back-button i {
            margin-right: 8px;
        }

        .content {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .details {
            flex: 1;
        }

        dl {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
            align-items: center;
        }

        dt {
            font-weight: bold;
            color: #555;
        }

        dd {
            margin: 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="user_info.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to User Information</a>
        <h1>User Details</h1>
        <div class="content">
            <div class="details">
                <dl>
                    <dt>Name:</dt>
                    <dd><?php echo htmlspecialchars($user['name']); ?></dd>
                    <dt>Vehicle:</dt>
                    <dd><?php echo htmlspecialchars($user['vehicle']); ?></dd>
                    <dt>Plate Number:</dt>
                    <dd><?php echo htmlspecialchars($user['plate_number']); ?></dd>
                    <dt>Contact Number:</dt>
                    <dd><?php echo htmlspecialchars($user['contact_number']); ?></dd>
                </dl>
            </div>
        </div>
    </div>
</body>
</html>
