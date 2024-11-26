<?php
session_start(); 

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); 
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
            display: flex;
            height: 100vh;
            background-color: #f0f0f0;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar {
            width: 300px;
            background-color: #ECECEC;
            color: #000522;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 20px 20px 0 0;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            transition: transform 0.3s ease-in-out;
            font-family: 'Inter', sans-serif;
        }

        .collapsed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar img {
            width: 250px;
            margin: 20px 0;
            transition: margin 0.3s;
        }

        .sidebar a {
            color: #787272;
            text-decoration: none;
            font-size: 16px;
            margin: 10px 0;
            padding: 10px;
            border-radius: 30px 0 30px 0;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar a i {
            margin-right: 30px;
            margin-left: 10px;
            width: 10px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
            color: #f0f0f0;
            transform: scale(1.10); 
        }

        .sidebar .highlighted {
            background-color: #2C2B6D;
            color: #f0f0f0;
        }

        .sidebar .logout {
            margin-top: auto;
            color: #787272;
            text-decoration: none;
            font-size: 16px;
            padding: 10px;
            border-radius: 30px 0 30px 0;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar .logout:hover {
            background-color: #0056b3;
            color: #f0f0f0;
        }

        .date-display {
            font-size: 14px;
            color: #787272;
            margin-top: 10px;
            text-align: left;
            font-family: 'Comfortaa', cursive;
            margin-left: 0;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s;
            margin-left: 310px;
            margin-top: 75px;
        }

        .collapsed .main-content {
            margin-left: 20px; 
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            color: #2C2B6D;
            font-family: 'Comfortaa', cursive;
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 30px;
            height: 30px;
            background-color: #2C2B6D;
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1000;
        }

        .logo-container {
            text-align: center;
        }

        .logo-container img {
            width: 200px; 
            height: auto;
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
            margin-top: 10px;
            margin-right: 10px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin: 0 0 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Inter', sans-serif; 
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        thead th {
            background-color: #2C2B6D;
            color: #f1f1f1;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        thead th:first-child {
            border-top-left-radius: 15px;
        }
        
        thead th:last-child {
            border-top-right-radius: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
            }

            .collapsed .sidebar {
                transform: translateX(-100%);
            }

            .collapsed .main-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 200px;
            }

            .collapsed .sidebar {
                transform: translateX(-100%);
            }

            .collapsed .main-content {
                margin-left: 80px; 
            }
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

    <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>
    
    <div class="sidebar">
        <div class="logo-container">
            <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Admin Dashboard Logo">
        </div>
        <a href="dashboard.php" ><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php"><i class="fas fa-users"></i> User Information</a>
        <a href="qr_code_management.php" ><i class="fas fa-qrcode"></i> QR Code Management</a>
        <a href="activity_logs.php" class="highlighted"><i class="fas fa-clipboard-list"></i> Activity Logs</a>
        <a href="login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="header-icons">
        
        <div class="admin-profile">
            A
            <div class="dropdown">
                <a href="login.php">Logout</a>
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
           <table id="timeLogTable">
    <thead>
        <tr>
            <th>User Information</th>
            <th>Vehicle</th>
            <th>Plate Number</th>
            <th>Time In</th>
            <th>Time Out</th>
        </tr>
    </thead>
    <tbody>
        <!-- Logged time entries will be added here dynamically -->
    </tbody>
</table>
        </div>
        <div style="height: 40px;"></div>
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

    const timeLogs = {};
    let isProcessing = false; 

    const scanButton = document.getElementById('scanButton');
    const videoContainer = document.getElementById('videoContainer');
    const output = document.getElementById('output');
    const timeLogTable = document.getElementById('timeLogTable').getElementsByTagName('tbody')[0];

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
                // Log time-in
                const newLog = { timeIn: currentTime, timeOut: null };
                timeLogs[userId].push(newLog);

                const newRow = timeLogTable.insertRow();
                newRow.insertCell(0).textContent = userName;       
                newRow.insertCell(1).textContent = vehicle;      
                newRow.insertCell(2).textContent = plateNumber; 
                newRow.insertCell(3).textContent = currentTime;  
                newRow.insertCell(4).textContent = "Not yet logged out"; 

                fetch('store_time.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ userId: userId, timeIn: currentTime })
                });

                output.textContent = `User ${userName} logged in at ${currentTime}`;
            } else {
               
                if (lastLog && lastLog.timeOut === null) { 
                    lastLog.timeOut = currentTime;

                    const rows = timeLogTable.getElementsByTagName('tr');
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].cells[0].textContent === userName && rows[i].cells[4].textContent === "Not yet logged out") {
                            rows[i].cells[4].textContent = lastLog.timeOut;

                          
                            fetch('store_time.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ userId: userId, timeOut: currentTime })
                            });

                            output.textContent = `User ${userName} logged out at ${currentTime}`;
                            break; 
                        }
                    }
                } else {
                    output.textContent = `User ${userName} has already logged out at ${lastLog.timeOut}`;
                }
            }
        } else {
            output.textContent = `Invalid QR code. User not found in database.`;
        }
    })
    .catch(error => {
        console.error('Error validating QR code:', error);
        output.textContent = 'Error validating QR code. Please try again.';
    })
    .finally(() => {
        setTimeout(() => { isProcessing = false; }, 3000);
    });
}

    document.addEventListener('DOMContentLoaded', () => {
    fetchLogsFromDatabase();

    const scanButton = document.getElementById('scanButton');
    scanButton.addEventListener('click', startCamera);
    
});

function fetchLogsFromDatabase() {
    fetch('fetch_time_logs.php')  
        .then(response => response.json())
        .then(logs => {
            const timeLogTable = document.getElementById('timeLogTable').getElementsByTagName('tbody')[0];
            timeLogTable.innerHTML = '';  
            
            logs.forEach(log => {
                const newRow = timeLogTable.insertRow();
                newRow.insertCell(0).textContent = log.user_id;
                newRow.insertCell(1).textContent = log.time_in;
                newRow.insertCell(2).textContent = log.time_out ? log.time_out : 'Not yet logged out';
            });
        })
        .catch(error => console.error('Error fetching logs:', error));
}

    document.addEventListener('DOMContentLoaded', () => {
    fetchLogsFromDatabase();

    const scanButton = document.getElementById('scanButton');
    scanButton.addEventListener('click', startCamera);
});

function fetchLogsFromDatabase() {
    fetch('fetch_time_logs.php')  
        .then(response => response.json())
        .then(logs => {
            const timeLogTable = document.getElementById('timeLogTable').getElementsByTagName('tbody')[0];
            timeLogTable.innerHTML = ''; 
            
            logs.forEach(log => {
                const newRow = timeLogTable.insertRow();
                newRow.insertCell(0).textContent = log.user_id;
                newRow.insertCell(1).textContent = log.time_in;
                newRow.insertCell(2).textContent = log.time_out ? log.time_out : 'Not yet logged out';
            });
        })
        .catch(error => console.error('Error fetching logs:', error));
}


</script>
    
</body>

</html>
