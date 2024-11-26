<?php
session_start(); 


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

include 'db_connect.php';

$userId = $_GET['id'] ?? '';

if ($userId) {
    $sql = "SELECT id, name, vehicle, plate_number, contact_number, qr_code_url, expiration_date FROM user_info WHERE id = ?";
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
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
            background-color: #2C2B6D;
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
            margin-right: 10px;
        }

        .content {
            display: flex;
            justify-content: space-between;
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
            margin-left: 20px;
            color: #666;
        }

        .qr-code {
            margin-left: 20px;
            text-align: center;
        }

        .qr-code img {
            width: 150px;
            height: 150px;
            margin-right: 20px;
            border-radius: 10px;
        }

        .qr-code h2 {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            margin-top: 10px;
            color: #2a3d6b;
            margin-right: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .action-button {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #2C2B6D;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 110px;
        }

        .action-button:hover {
            background-color: #0056b3;
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
                <dt>QR Code Expiration Date:</dt>
                <dd><?php echo htmlspecialchars($user['expiration_date']); ?></dd>
            </dl>
        </div>

        <div class="qr-code">
    <?php if (!empty($user['qr_code_url'])): ?>
        <img src="<?php echo htmlspecialchars($user['qr_code_url']); ?>" alt="QR Code" id="qrCodeImage">
        <h2>Generated QR Code</h2>
        <div class="button-container">
            <button onclick="printQRCode('<?php echo htmlspecialchars($user['plate_number']); ?>')" class="action-button">Print</button>
            <button onclick="downloadQRCode()" class="action-button">Download</button>
        </div>
    <?php else: ?>
        <h2>No QR Code generated for this user.</h2>
    <?php endif; ?>
</div>

</div>

<script>

function printQRCode(plateNumber) {
    
    const printSection = document.createElement('div');
    printSection.id = 'printSection';
    printSection.style.display = 'none';

   
    const qrCodeImage = document.getElementById('qrCodeImage').src;

    
    printSection.innerHTML = `
        <div style="text-align: center; font-family: Arial, sans-serif;">
            <img src="${qrCodeImage}" alt="QR Code" style="margin-bottom: 20px;">
            <h2>${plateNumber}</h2>
        </div>
    `;

   
    document.body.appendChild(printSection);

   
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = printSection.innerHTML;

    window.print();

    
    document.body.innerHTML = originalContent;
    window.location.reload(); 
}


    function downloadQRCode() {
        const qrCodeImage = document.getElementById('qrCodeImage').src;
        const userName = "<?php echo htmlspecialchars($user['name']); ?>"; 
        const sanitizedUserName = userName.replace(/\s+/g, '_').replace(/[^\w\-]/g, ''); 

        const canvas = document.createElement('canvas');
        const img = new Image();
        img.crossOrigin = 'anonymous'; 
        img.src = qrCodeImage;

        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);

            canvas.toBlob(function(blob) {
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `${sanitizedUserName}_qr_code.png`; 
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }, 'image/png');
        };
    }

</script>

</body>
</html>
