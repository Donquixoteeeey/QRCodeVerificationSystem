<?php
include 'db_connect.php'; // Ensure the path is correct

// Fetch data from the database
$sql = "SELECT id, name, vehicle, plate_number, contact_number FROM user_info"; // Adjust table and columns as needed
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
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
            width: 300px; /* Adjust width if needed */
            background-color: #ECECEC;
            color: #000522;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 20px 20px 0 0;
            position: relative;
            font-family: 'Inter', sans-serif; /* Apply Inter font here */
        }

        .sidebar img {
            width: 250px;
            margin-bottom: 30px;
        }

        .sidebar a {
            width: 100%;
            color: #787272;
            text-decoration: none;
            font-size: 16px; /* Adjusted font size */
            margin: 10px 0; /* Margin to separate the links */
            padding: 10px;
            border-radius: 30px 0 30px 0;
            display: flex;
            align-items: center;
            box-sizing: border-box;
        }

        .sidebar a i {
            margin-right: 25px; /* Space between icon and text */
            margin-left: 10px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
            color: #f0f0f0;
        }

        .sidebar .highlighted {
            background-color: #2C2B6D; /* Highlight color for the active link */
            color: #f0f0f0;
        }

        .sidebar .logout {
            margin-top: auto; /* Moves the logout link to the bottom */
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

        .header-icons {
    position: fixed;
    top: 15px;
    right: 20px;
    display: flex;
    align-items: center;
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
    position: relative; /* Ensure dropdown is positioned relative to this */
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


        .main-content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
            margin-left: 20px; /* Adjust margin to make space for the sidebar */
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            color: #2C2B6D;
            margin-bottom: 10px;
            margin-left: 20px;
            margin-top: 50px;
            font-family: 'Comfortaa', cursive; /* Apply Comfortaa font here */
        }

        .date-display {
            font-size: 14px;
            color: #787272;
            margin-top: 10px;
            margin-bottom: 30px;
            text-align: center;
            font-family: 'Comfortaa', cursive;
            margin-left: 20px;
            margin-top: 10px;
            text-align: left;
        }

        .table-container {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
            font-family: 'Inter', sans-serif; /* Apply Inter font here */
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2C2B6D;
            color: #f1f1f1;
            border-radius: 20px 20px 0px 0;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #007bff; /* Blue */
            border-radius: 20px;
        }

        .btn-delete {
            background-color: #dc3545; /* Red */
            border-radius: 20px;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-add {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 0 20px;
            border: none;
            border-radius: 5px;
            background-color: #28a745; /* Green */
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
            font-family: 'Inter', sans-serif; /* Apply Inter font here */
            border-radius: 20px;
        }

        .btn-add:hover {
            background-color: #218838;
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
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php" class="highlighted"><i class="fas fa-users"></i> User Information</a>
        <a href="qr_management.php"><i class="fas fa-qrcode"></i> QR Code Management</a>
        <a href="activity_logs.php"><i class="fas fa-clipboard-list"></i> Activity Logs</a>
        <a href="login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="header-icons">
    <i class="fas fa-bell notification-bell"></i>
    <div class="separator"></div>
    <i class="fas fa-cog settings-icon"></i>
    <div class="admin-profile">
        A
        <div class="dropdown">
            <a href="login.php">Logout</a>
        </div>
    </div>
</div>

    <div class="main-content">
        <div class="dashboard-title">User Information</div>
        <div class="date-display"><?php echo date('F j, Y'); ?></div>
        <div class="table-container">
            <a href="add_user.php" class="btn-add"><i class="fas fa-plus"></i> Add User</a>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Vehicle</th>
                        <th>Plate Number</th>
                        <th>Contact Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['vehicle']}</td>
                                    <td>{$row['plate_number']}</td>
                                    <td>{$row['contact_number']}</td>
                                    <td>
                                        <a href='edit_user.php?id={$row['id']}' class='btn btn-edit'><i class='fas fa-edit'></i> Edit</a>
                                        <a href='delete_user.php?id={$row['id']}' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this user?\");'><i class='fas fa-trash'></i> Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
