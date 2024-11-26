<?php
session_start(); 

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: phone_login.php'); // Redirect to login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Istok Web', sans-serif;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .main-content {
            padding: 20px;
            margin-top: 75px;
            margin-left: 20px;
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            color: #2C2B6D;
            font-family: 'Comfortaa', cursive;
        }

        .header-icons {
            position: fixed;
            top: 15px;
            right: 20px;
            display: flex;
            gap: 20px;
            z-index: 1000;
        }

        .admin-profile {
            width: 40px;
            height: 40px;
            background-color: #d3d3d3;
            color: #000522;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            position: relative;
        }

        .admin-profile:hover {
            background-color: #b0b0b0;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 30px;
        }

        .card h3 {
            font-size: 30px;
            color: #000522;
            font-family: 'Comfortaa', cursive;
            margin-top: 20px;
        }

        .scan-button {
            background: linear-gradient(135deg, #4a90e2, #50e3c2);
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background 0.3s, box-shadow 0.3s;
            width: 250px;
            font-family: 'Inter', sans-serif;
        }

        .scan-button:hover {
            background: linear-gradient(135deg, #50e3c2, #4a90e2);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 400px;
            display: none;
            margin-top: 20px;
            overflow: hidden;
        }

        .video-container video {
            width: 500px;
            height: 100%;
            border-radius: 15px;
            object-fit: cover;
        }

        .output {
            margin-top: 20px;
            font-size: 20px;
            color: #2C2B6D;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 10px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                margin-left: 5px;
            }

            .scan-button {
                width: 100%;
            }

            .video-container video {
                width: 100%;
            }
        }

        .date-display {
            font-size: 14px;
            color: #787272;
            margin-top: 10px;
            text-align: left;
            font-family: 'Comfortaa', cursive;
            margin-left: 0;
        }

        .admin-profile {
            width: 40px;
            height: 40px;
            background-color: #d3d3d3;
            color: #000522;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            position: relative; 
        }
        
        .admin-profile:hover {
            background-color: #b0b0b0;
        }
        
        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin-top: 5px;
        }
        
        .dropdown a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #3a3a3a;
            font-size: medium;
        }
        
        .dropdown a:hover {
            background-color: #ddd;
            border-radius: 15px;
        }
        
        .admin-profile:hover .dropdown {
            display: block;
        }
    
    </style>

</head>

<body>

 
 <audio id="beepSound" src="sound/beep-104060.mp3"></audio>
 

    <div class="header-icons">
        <div class="admin-profile">A
            <div class="dropdown">
                <a href="phone_login.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-title">Activity Logs</div>
        <div class="date-display"></div>

        <div class="card">
            <h3>Scan QR Code</h3>
            <button id="scanButton" class="scan-button">Start Camera</button>
            <div class="video-container" id="videoContainer">
                <video id="video" autoplay></video>
            </div>
            <div class="output" id="output"></div>
        </div>
    </div>

    <script src="https://unpkg.com/@zxing/library@latest"></script>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('collapsed');
        }

        const profile = document.querySelector('.admin-profile');
        const dropdown = document.querySelector('.dropdown');

        profile.addEventListener('click', () => {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (event) => {
            if (!profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const dateDisplay = document.querySelector('.date-display');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date();
            dateDisplay.textContent = today.toLocaleDateString('en-US', options);
        });

       
        const beepSound = document.getElementById('beepSound');

        const timeLogs = {};
        let isProcessing = false;

        const scanButton = document.getElementById('scanButton');
        const videoContainer = document.getElementById('videoContainer');
        const output = document.getElementById('output');

        scanButton.addEventListener('click', startCamera);

        function startCamera() {
            const userConfirmed = confirm("Do you want to use the camera for QR code scanning?");
            if (!userConfirmed) {
                output.textContent = "Camera access denied. Please allow camera access to scan QR codes.";
                return;
            }

            videoContainer.style.display = 'block';
            const codeReader = new ZXing.BrowserQRCodeReader();

            const constraints = {
                video: {
                    facingMode: "environment"
                }
            };

            codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
                if (result) {
                    logTime(result.text);
                } else if (err && err.name === 'NotAllowedError') {
                    output.textContent = "Camera access denied. Please enable camera access in your browser settings.";
                } else if (err) {
                    console.error(err);
                }
            }, constraints);
        }

        function logTime(qrCodeText) {
    if (isProcessing) return;
    isProcessing = true;

    fetch('validate_qr_code.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ qr_code_text: qrCodeText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const userId = data.user.id;
            const userName = data.user.name;
            const vehicle = data.user.vehicle;
            const plateNumber = data.user.plate_number;
            const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            if (!timeLogs[userId]) {
                timeLogs[userId] = [];
            }
            
            const lastLog = timeLogs[userId][timeLogs[userId].length - 1];
            
            if (!lastLog || lastLog.timeOut) {
                // Time-in action
                const newLog = { timeIn: currentTime, timeOut: null };
                timeLogs[userId].push(newLog);
                
                output.textContent = `User ${userName} logged in at ${currentTime}`;
                
                fetch('store_time.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ userId: userId, timeIn: currentTime })
                });

                
                beepSound.play();
            
            } else {
                
                if (lastLog && lastLog.timeOut === null) {
                    lastLog.timeOut = currentTime;
                    
                    output.textContent = `User ${userName} logged out at ${currentTime}`;
                    
                    fetch('store_time.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ userId: userId, timeOut: currentTime })
                    });

                   
                    beepSound.play();
                }
            }
            
            setTimeout(() => {
                isProcessing = false;  
                }, 3000);
        } else {
            output.textContent = "Invalid QR code. Please try again.";
            
            
            beepSound.play();
            
            isProcessing = false;  
        }
    })
    .catch(err => {
        console.error(err);
        output.textContent = "Error processing QR code. Please try again.";
        isProcessing = false; 
    });
}


    </script>

</body>

</html>
