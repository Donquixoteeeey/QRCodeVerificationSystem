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
        }

        .card h3 {
            margin: 0 0 20px;
            font-size: 24px;
            color: #000522;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Admin Dashboard Logo">
        <a href="dashboard.php" class="highlighted"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php"><i class="fas fa-users"></i> User Information</a>
        <a href="qr_code_management.php"><i class="fas fa-qrcode"></i> QR Code Management
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
        <div class="dashboard-title">Dashboard</div>
        <div class="date-display"></div>
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
    </script>
</body>
</html>
