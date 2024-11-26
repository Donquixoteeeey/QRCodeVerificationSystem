<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); 
    exit();
}

include 'db_connect.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

  
    $user_query = "SELECT name, vehicle, plate_number FROM user_info WHERE id = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_name = $user_row['name'];
        $vehicle = $user_row['vehicle'];
        $plate_number = $user_row['plate_number'];
    } else {
        echo "User not found.";
        exit;
    }

    
    $limit = 20; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    $offset = ($page - 1) * $limit; 

    $date_filter_query = '';
    $date_filter_params = [];
    $param_types = 'i'; 

  
    if (isset($_GET['date_range'])) {
        $date_range = $_GET['date_range'];

        if ($date_range == '7_days') {
            $date_filter_query = " AND time_timestamp >= NOW() - INTERVAL 7 DAY";
        } elseif ($date_range == '30_days') {
            $date_filter_query = " AND time_timestamp >= NOW() - INTERVAL 30 DAY";
        } elseif ($date_range == '60_days') {
            $date_filter_query = " AND time_timestamp >= NOW() - INTERVAL 60 DAY";
        } elseif ($date_range == 'custom' && isset($_GET['start_date']) && isset($_GET['end_date'])) {
            
            $start_date = trim($_GET['start_date']);
            $end_date = trim($_GET['end_date']);
            
           
            
         
            $date_filter_query = " AND time_timestamp BETWEEN ? AND ?";
            
            $date_filter_params[] = $start_date . ' 00:00:00'; 
            $date_filter_params[] = $end_date . ' 23:59:59';  
       
            $param_types .= 'ss'; 
        }
        
    }

    
    $activity_query = "SELECT time_timestamp, action_type FROM user_time_logs WHERE user_id = ? $date_filter_query LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($activity_query);


    $bind_params = [];
    if (!empty($date_filter_params)) {
        
        $bind_params[] = &$user_id; 

      
        foreach ($date_filter_params as &$param) {
            $bind_params[] = &$param; 
        }

    
        $bind_params[] = &$limit; 
        $bind_params[] = &$offset; 

    
        $param_types = 'i' . str_repeat('s', count($date_filter_params)) . 'ii';
        
       
        array_unshift($bind_params, $param_types); 
        call_user_func_array([$stmt, 'bind_param'], $bind_params);
    } else {
        
        $stmt->bind_param("iii", $user_id, $limit, $offset);
    }

   
    $stmt->execute();
    $result = $stmt->get_result();

    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }

    
    $count_query = "SELECT COUNT(*) as total FROM user_time_logs WHERE user_id = ? $date_filter_query";
    $count_stmt = $conn->prepare($count_query);

  
    $bind_count_params = [$user_id]; 

   
    if (!empty($date_filter_params)) {
        
        $bind_count_params = array_merge([$user_id], $date_filter_params);
    }

   
    $bind_count_types = 'i'; 
    if (count($date_filter_params) > 0) {
        $bind_count_types .= str_repeat('s', count($date_filter_params)); 
    }

    
    $count_stmt->bind_param($bind_count_types, ...$bind_count_params); 

  
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_row = $count_result->fetch_assoc();
    $total_activities = $total_row['total'];
    $total_pages = ceil($total_activities / $limit); 

  
    $stmt->close();
    $user_stmt->close();
    $count_stmt->close();
} else {
    echo "No user ID provided.";
    exit;
}


$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activities</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
        }
        h1 {
            font-size: 2em;
            font-weight: 300;
            color: #333;
            text-align: center;
            margin-top: 30px;
            font-family: 'Comfortaa', cursive;
            color: #2C2B6D;
        }
      
        table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow: hidden;
            border-radius: 8px;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #2C2B6D;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #eaf3ff;
        }
        tbody td {
            font-family: 'Inter', sans-serif;
            color: #555;
        }
        tbody td:first-child {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #333;
        }
        .empty-state {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        @media (max-width: 600px) {
            table, th, td {
                font-size: 14px;
            }
            h1 {
                font-size: 1.5em;
            }
        }
        .back-button {
            display: inline-block;
            margin: 20px 0 20px 80px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #2C2B6D;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            text-align: center;
            text-decoration: none; 
        }
        .back-button:hover {
            background-color: #4342a5;
        }
        .user-info {
    text-align: center;
    margin-bottom: 20px;
    color: #555;
    font-size: 1.1em;
    padding: 10px; 
    margin: 20px auto; 
    max-width: 600px;
}
.pagination {
    text-align: center;
    margin: 20px 0;
    margin-top: 25px;
}

.pagination a {
    display: inline-block;
    margin: 0 10px;
    padding: 10px 15px;
    font-size: 16px;
    color: #fff;
    background-color: #2C2B6D;
    border-radius: 20px;
    text-decoration: none;
    transition: background-color 0.3s;
    width: 120px;
    text-align: center; 
}

.pagination a:hover {
    background-color: #0056b3;
}

.icon-spacing {
    margin-right: 10px; 
}

.date-range-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
}

.date-range-select,
input[type="date"] {
    padding: 10px 15px;
    font-size: 16px;
    border: 2px solid #2C2B6D;
    border-radius: 15px;
    margin-right: 10px;
    margin-bottom: 20px;
    margin-top: 30px;
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 200px; 
    font-family: 'Inter', sans-serif; 
    font-size: 15px;
}

.date-range-select:focus,
input[type="date"]:focus {
    border-color: #4342a5; 
    box-shadow: 0 0 5px rgba(66, 66, 205, 0.5); 
    outline: none;
    font-family: 'Inter', sans-serif; 
   
}

.filter-button {
    padding: 11px 20px;
    font-size: 15px;
    color: #fff;
    background-color: #2C2B6D;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-left: 10px;
    margin-bottom: 10px;
    margin-top: 14px;
    
}

.filter-button:hover {
    background-color: #0056b3; 
}

.date-label {
    margin-right: 10px;
    margin-left: 10px;
}

    </style>
</head>
<body>
<a href="dashboard.php" class="back-button"><i class="fas fa-arrow-left icon-spacing"></i>Back to Dashboard</a>
<h1>User <?php echo htmlspecialchars($user_name); ?>'s Activity</h1>
<div class="user-info">
    <p>Vehicle: <?php echo htmlspecialchars($vehicle); ?></p>
    <p>Plate Number: <?php echo htmlspecialchars($plate_number); ?></p>
</div>

<form method="GET" style="margin-bottom: 20px;">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
    
    <div class="date-range-container""> 
    <select name="date_range" id="dateRangeSelect" class="date-range-select" onchange="toggleCustomDateInputs()">
    <option value="" <?php echo empty($_GET['date_range']) ? 'selected' : ''; ?>>Select Date Range</option>
    <option value="7_days" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === '7_days') ? 'selected' : ''; ?>>Last 7 Days</option>
    <option value="30_days" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === '30_days') ? 'selected' : ''; ?>>Last 30 Days</option>
    <option value="60_days" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === '60_days') ? 'selected' : ''; ?>>Last 60 Days</option>
    <option value="custom" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === 'custom') ? 'selected' : ''; ?>>Custom Range</option>
</select>

        
        <div id="customDateInputs" style="display: <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === 'custom') ? 'block' : 'none'; ?>; margin-top: 10px; display: inline-block;">
    <label for="start_date" class="date-label">Start Date:</label>
    <input type="date" name="start_date" id="start_date" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>" class="date-input">
    <label for="end_date" class="date-label">End Date:</label>
    <input type="date" name="end_date" id="end_date" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>" class="date-input">
</div>

        
        <button type="submit" class="filter-button">Filter</button>
    </div>
</form>




<table>
    <thead>
        <tr>
            <th>Date/Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($activities)): ?>
            <tr>
                <td colspan="2" class="empty-state">No activities found for this user.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?php echo date("Y-m-d H:i:s", strtotime($activity['time_timestamp'])); ?></td>
                    <td><?php echo htmlspecialchars($activity['action_type']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?user_id=<?php echo urlencode($user_id); ?>&page=<?php echo $page - 1; ?>" class="back-button">Previous</a>
    <?php endif; ?>
    
    <?php if ($page < $total_pages): ?>
        <a href="?user_id=<?php echo urlencode($user_id); ?>&page=<?php echo $page + 1; ?>" class="back-button">Next</a>
    <?php endif; ?>
</div>

<script>
   function toggleCustomDateInputs() {
    const dateRangeSelect = document.getElementById('dateRangeSelect');
    const customDateInputs = document.getElementById('customDateInputs');
    customDateInputs.style.display = dateRangeSelect.value === 'custom' ? 'inline-block' : 'none';
}


window.onload = toggleCustomDateInputs;


</script>

</body>
</html>
