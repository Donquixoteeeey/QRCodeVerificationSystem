<?php
session_start(); 


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); 
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "qr_code_management");


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query_with_qr_code = "SELECT id, name, vehicle, plate_number FROM user_info WHERE qr_code_generated_at IS NOT NULL";
$result_with_qr_code = $mysqli->query($query_with_qr_code);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM user_info WHERE qr_code_generated_at IS NOT NULL";
$result_with_qr_code = $conn->query($sql);


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
            font-size: 30px;
            color: #000522;
            font-family: 'Comfortaa', cursive;
            margin-top: 20px;
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

        .qr-code-form input[type="submit"] {
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
            margin-top: 20px;
            font-family: 'Inter', sans-serif; 
        }
        
        .qr-code-form input[type="submit"]:hover {
            background: linear-gradient(135deg, #50e3c2, #4a90e2);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
        
        .qr-code-form input[type="text"] {
            width: calc(500px - 22px); 
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
            background-color: #2C2B6D;
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
            border-top: 0.5px solid #ccc; 
            margin: 30px 20px; 
        }

        .table-container {
            text-align: center;
    background-color: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    margin-top: 30px;
    height: 500px;
    overflow-x: auto;
    overflow-y: auto;
}

.table-container h2{
    margin: 0 0 20px;
            font-size: 30px;
            color: #000522;
            font-family: 'Comfortaa', cursive;
            margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Inter', sans-serif;
    margin-bottom: 10px;
}

thead th {
    background-color: #2C2B6D;
    color: #f1f1f1;
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

tbody {
    display: block;
    height: 320px;
    overflow-y: auto;
}

tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

td, th {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    width: 20%;
}

thead th:first-child {
    border-top-left-radius: 15px;
}

thead th:last-child {
    border-top-right-radius: 15px;
}

tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

form input[type="text"] {
    padding: 5px;
    width: 30%;
    border: 1px solid #ccc;
    border-radius: 20px;
    font-size: 15px;
    margin-bottom: 20px;
    margin-top: 10px;
    font-family: Inter, sans-serif;
}

form input[type="text"] :focus {
    border: 1px solid #2C2B6D;
}

form button {
    padding: 10px 15px;
    background-color: #2C2B6D;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
}

form button:hover {
    background-color: #0056b3;
}

button[type="submit"] {
    padding: 5px 20px;
    background-color: #2C2B6D;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 15px;
    margin-left: 10px;
    font-family: Inter, sans-serif;
    margin-top: 20px;
}

button[type="submit"]:hover {
    background-color: #0056b3;
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
        <a href="qr_code_management.php" class="highlighted"><i class="fas fa-qrcode"></i> QR Code Management</a>
        <a href="activity_logs.php"><i class="fas fa-clipboard-list"></i> Activity Logs</a>
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
    <div class="dashboard-title">QR Code Management</div>
    <div class="date-display"></div>

    <div class="card">
            <h3>QR Code Generator</h3>
            <form class="qr-code-form" action="generate_qr_code.php" method="POST">
                <input type="text" id="searchInput" placeholder="Search User by Name" onkeyup="searchUsers()">
                <input type="hidden" name="user_id">
                <input type="submit" value="Generate QR Code">
            </form>
            <div class="separator-m"></div>
        
        <div class="qr-code-result">
            <?php
            if (isset($_GET['qr_code_url'])) {
                $qr_code_url = htmlspecialchars($_GET['qr_code_url']);
                echo '<img id="qrImage" src="' . $qr_code_url . '" alt="QR Code">';
            }
            ?>
        </div>
        
    </div>

    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <ul id="userList"></ul>
        </div>
    </div>

    <div class="table-container">
    <h2>Print QR Codes</h2>
    
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name, vehicle, or plate number" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>

    <form action="process_selection.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Name</th>
                    <th>Vehicle</th>
                    <th>Plate Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                $sql = "SELECT * FROM user_info WHERE qr_code_generated_at IS NOT NULL";
                if (!empty($search)) {
                    $sql .= " AND (name LIKE '%$search%' OR vehicle LIKE '%$search%' OR plate_number LIKE '%$search%')";
                }
                $result_with_qr_code = $conn->query($sql);

                if ($result_with_qr_code->num_rows > 0) {
                    while ($row = $result_with_qr_code->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selected_users[]' value='" . $row['id'] . "'></td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['vehicle']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['plate_number']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users with QR codes found.</td></tr>";
                }
                ?>
                
            </tbody>
        </table>
        <button type="submit">Print</button>
    </form>
</div>

<div style="height: 40px;"></div>

</div>


</div>

    <script>
        function searchTable() {
    let searchTerm = document.getElementById('searchInput').value;

   
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'qr_code_management.php?search=' + searchTerm, true); 
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('tableBody').innerHTML = xhr.responseText; 
        }
    };
    xhr.send();
}
    

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
           const dateDisplay = document.querySelector('.date-display'); const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }; 
           const today = new Date(); dateDisplay.textContent = today.toLocaleDateString('en-US', options); })
           
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
                    
                    li.textContent = `${user.name} - ${user.vehicle} (Plate: ${user.plate_number})`;
                    li.dataset.id = user.id;
                    li.onclick = function() {
                        document.querySelector('input[name="user_id"]').value = this.dataset.id;
                        document.getElementById('searchInput').value = this.textContent.split(' - ')[0]; 
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

        function downloadQRCode() {
            const qrImage = document.getElementById('qrImage');  
            if (qrImage) {
                const qrURL = qrImage.src;  

                const link = document.createElement('a');
                link.href = qrURL;  
                link.download = 'qr_code.png';  

                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
            } else {
                alert('No QR code available to download.');
            }
        }

        function printQRCode() {
            const qrImage = document.getElementById('qrImage');
            if (qrImage) {
                const newWindow = window.open('', '', 'height=600,width=800');
                newWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
                newWindow.document.write('<img src="' + qrImage.src + '" style="width: 200px; height: auto;"/>'); 
                newWindow.document.write('</body></html>');
        
                newWindow.document.close();
                newWindow.focus();
                newWindow.print();
            } else {
                alert('No QR code available to print.');
            }
        }

        function printSelectedQRCodes() {
    const qrCodesDiv = document.querySelector('.qr-codes-to-print');
    
    if (qrCodesDiv) {
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Selected QR Codes</title></head><body>');
        printWindow.document.write(qrCodesDiv.innerHTML);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    } else {
        alert('No QR codes available to print.');
    }
}

    </script>

</body>

</html>
