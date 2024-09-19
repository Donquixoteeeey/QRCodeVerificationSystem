<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Management</title>
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
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        .sidebar img {
            width: 250px;
            margin-bottom: 30px;
        }

        .sidebar a {
            width: 100%;
            color: #787272;
            text-decoration: none;
            font-size: 16px;
            margin: 10px 0;
            padding: 10px;
            border-radius: 30px 0 30px 0;
            display: flex;
            align-items: center;
            box-sizing: border-box;
        }

        .sidebar a i {
            margin-right: 25px;
            margin-left: 10px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
            color: #f0f0f0;
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
            box-sizing: border-box;
        }

        .sidebar .logout i {
            margin-right: 25px;
        }

        .sidebar .logout:hover {
            background-color: #0056b3;
            color: #f0f0f0;
        }

        .header-icons {
            position: fixed;
            top: 15px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 1000;
        }

        .notification-bell,
        .settings-icon,
        .admin-profile {
            font-size: 18px;
            cursor: pointer;
            color: #787272;
        }

        .separator {
            height: 26px;
            border-left: 1px solid #3a3a3a;
            background-color: #0000FF;
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
        }

        .admin-profile:hover {
            background-color: #b0b0b0;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            border-radius: 30px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
            margin-left: 20px;
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            color: #2C2B6D;
            margin-bottom: 10px;
            margin-left: 20px;
            margin-top: 50px;
            font-family: 'Comfortaa', cursive;
        }

        .date-display {
            font-size: 14px;
            color: #787272;
            margin-top: 10px;
            text-align: center;
            font-family: 'Comfortaa', cursive;
            margin-left: 20px;
            margin-top: 10px;
            text-align: left;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
            margin-top: 30px;
           
        }

        .card h3 {
            margin: 0 0 20px;
            font-size: 25px;
            color: #000522;
            font-family: 'Inter', sans-serif; 
            margin-top: 10px;
        }

        .card a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #000522;
            color: #f0f0f0;
            border-radius: 10px;
            text-decoration: none;
            font-size: 18px;
            font-family: 'Istok Web', sans-serif;
        }

        .card a:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
            }

            .sidebar img {
                width: 150px;
            }

            .sidebar a {
                font-size: 14px;
            }

            .header-icons {
                right: 10px;
                gap: 15px;
            }

            .dashboard-title {
                font-size: 20px;
                margin-bottom: 10px;
            }

            .main-content {
                margin-left: 250px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 200px;
            }

            .sidebar img {
                width: 100px;
            }

            .sidebar a {
                font-size: 12px;
            }

            .header-icons {
                right: 5px;
                gap: 10px;
            }

            .dashboard-title {
                font-size: 18px;
                margin-bottom: 5px;
            }

            .main-content {
                margin-left: 200px;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 25px;
            width: 80%;
            max-width: 600px;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease;
        }

        .modal-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .modal-content ul li {
            background: #fff;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 20px;
            cursor: pointer;
            border: 1px solid #ddd;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .modal-content ul li:hover {
            background-color: #f5f5f5;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: #000;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* QR Code Button Styles */
.qr-code-form input[type="submit"] {
    background: linear-gradient(135deg, #4a90e2, #50e3c2);
    border: none;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    font-family: 'Istok Web', sans-serif;
    border-radius: 25px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background 0.3s, box-shadow 0.3s;
    width: 250px;
    margin-top: 20px;
    font-family: 'Inter', sans-serif; 
}

.qr-code-form input[type="submit"]:hover {
    background: linear-gradient(135deg, #50e3c2, #4a90e2);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.qr-code-form input[type="text"] {
    width: calc(500px - 22px); /* Adjust width considering padding */
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 25px;
    font-size: 16px;
    margin-bottom: 15px;
    box-sizing: border-box;
    margin-right: 15px;
    font-family: 'Inter', sans-serif; 
}

.qr-code-result img {
            margin-top: 20px;
            width: 150px;
            height: 150px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container button {
            background-color: #000522;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 16px;
            width: 150px;
            font-family: 'Inter', sans-serif; 
        }

        .button-container button:hover {
            background-color: #0056b3;
        }

        .separator-m {
            margin-top: 20px;
  border-top: 0.5px solid #ccc; /* Adjust color and thickness as needed */
  margin: 30px 20px; /* Adjust spacing as needed */
}

    </style>
</head>
<body>
    <!-- Sidebar and other elements -->
    <div class="sidebar">
        <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Admin Dashboard Logo">
        <a href="dashboard.php" ><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php"><i class="fas fa-users"></i> User Information</a>
        <a href="qr_code_management.php" class="highlighted"><i class="fas fa-qrcode"></i> QR Code Management</a>
        <a href="time_management.php"><i class="fas fa-clock"></i> Time In/Out Management</a>
        <a href="activity_logs.php"><i class="fas fa-clipboard-list"></i> Activity Logs</a>
        <a href="login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="header-icons">
        <i class="fas fa-bell notification-bell"></i>
        <div class="separator"></div>
        <i class="fas fa-cog settings-icon"></i>
        <div class="admin-profile">
            <span>A</span>
            <div class="dropdown">
                <a href="login.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="dashboard-title">QR Code Management</div>
        <div class="date-display"></div>
        <!-- QR Code Form -->
        <div class="card">
            <h3>QR Code Generator</h3>
            <form class="qr-code-form" action="generate_qr_code.php" method="POST">
                <input type="text" id="searchInput" placeholder="Search User by Name" onkeyup="searchUsers()">
                <input type="hidden" name="user_id">
                <input type="submit" value="Generate QR Code">
            </form>
            <div class="separator-m"></div>
        <!-- Display QR Code -->
        <div class="qr-code-result">
            <?php
            if (isset($_GET['qr_code_url'])) {
                $qr_code_url = htmlspecialchars($_GET['qr_code_url']);
                echo '<img id="qrImage" src="' . $qr_code_url . '" alt="QR Code">';
            }
            ?>
        </div>
        <div class="button-container">
            <button onclick="downloadQRCode()">Download</button>
            <button onclick="printQRCode()">Print</button>
        </div>
    </div>

    <!-- User List Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <ul id="userList"></ul>
        </div>
    </div>

    <script>
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
            const currentDate = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = currentDate.toLocaleDateString(undefined, options);
            dateDisplay.textContent = formattedDate;
        });

        function searchUsers() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const userList = document.getElementById('userList');
            const userModal = document.getElementById('userModal');

            if (input.length < 2) {
                userModal.style.display = 'none';
                return;
            }

            fetch('search_users.php?name=' + encodeURIComponent(input))
                .then(response => response.json())
                .then(data => {
                    userList.innerHTML = '';
                    data.forEach(user => {
                        const li = document.createElement('li');
                        li.textContent = user.name;
                        li.dataset.id = user.id;
                        li.onclick = function() {
                            document.querySelector('input[name="user_id"]').value = this.dataset.id;
                            document.getElementById('searchInput').value = this.textContent;
                            userModal.style.display = 'none';
                        };
                        userList.appendChild(li);
                    });
                    userModal.style.display = 'flex';
                })
                .catch(error => console.error('Error:', error));
        }

        const closeModal = document.querySelector('.modal-close');
        closeModal.onclick = function() {
            const userModal = document.getElementById('userModal');
            userModal.style.display = 'none';
        };

        window.onclick = function(event) {
            const userModal = document.getElementById('userModal');
            if (event.target === userModal) {
                userModal.style.display = 'none';
            }
        };

        // Function to download the QR code
        function downloadQRCode() {
            const qrImage = document.getElementById('qrImage');  // Get the QR code image element
            if (qrImage) {
                const qrURL = qrImage.src;  // Get the image URL

                // Create a temporary link element
                const link = document.createElement('a');
                link.href = qrURL;  // Set the link href to the QR code image URL
                link.download = 'qr_code.png';  // Set the download attribute with the file name

                // Append link to the document and simulate a click to trigger download
                document.body.appendChild(link);
                link.click();

                // Remove the temporary link from the document
                document.body.removeChild(link);
            } else {
                alert('No QR code available to download.');
            }
        }

        // Function to print the QR code
        function printQRCode() {
            const qrImage = document.getElementById('qrImage');  // Get the QR code image element
            if (qrImage) {
                // Open a new window and insert the QR code image into the window
                const newWindow = window.open('', '', 'height=600,width=800');
                newWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
                newWindow.document.write('<img src="' + qrImage.src + '" style="width: 100%; height: auto;"/>');
                newWindow.document.write('</body></html>');
                
                // Close the document to ensure it loads fully
                newWindow.document.close();
                newWindow.focus();
                newWindow.print();
            } else {
                alert('No QR code available to print.');
            }
        }
    </script>
</body>
</html>
