<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_users'])) {
    $selectedUsers = $_POST['selected_users'];

    if (!empty($selectedUsers)) {
        $ids = implode(",", array_map('intval', $selectedUsers));  
        $query = "SELECT name, plate_number, qr_code_url FROM user_info WHERE id IN ($ids)";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<div class='qr-codes-to-print'>";
            $counter = 0;  
            while ($row = $result->fetch_assoc()) {
                if ($counter % 4 == 0 && $counter != 0) {
                    echo "</div><div class='qr-codes-to-print'>";  
                }
                echo "<div class='qr-item'>";
                echo "<p>{$row['name']}</p>";
                echo "<img src='{$row['qr_code_url']}' alt='QR Code' style='width: 150px; height: 150px;' />";
                echo "<p>Plate Number: {$row['plate_number']}</p>";
                echo "</div>";
                $counter++;
            }
            echo "</div>";  
            echo "<div class='print-button-container'>
            <button class='cancel-button' onclick='cancelPrint()'>Cancel</button>
                    <button class='print-button' onclick='printSelectedQRCodes()'>Print Selected QR Codes</button>
                    
                  </div>";
        } else {
            echo "No QR codes found for selected users.";
        }
    } else {
        echo "No users selected.";
    }
}
$conn->close();
?>

<script type="text/javascript">
    function printSelectedQRCodes() {
        window.print();  
    }

    function cancelPrint() {
        
        window.location.href = "qr_code_management.php";  
    }
</script>

<style>
    body{
        font-family: Inter, sans-serif;
        font-weight: 200;
    }
    
    .qr-codes-to-print {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 20px;  
        margin-bottom: 20px;
    }

    .qr-item {
        text-align: center;
    }

    .qr-item img {
        max-width: 100%;
        height: auto;
    }

    
    .print-button-container {
        display: flex;
        justify-content: center; 
        align-items: center; 
        margin-top: 20px;
        padding: 20px 0; 
    }

  
    .print-button, .cancel-button {
        background-color: #2C2B6D; 
        color: white; 
        padding: 10px 0; 
        font-size: 15px;  
        border: none;  
        border-radius: 15px; 
        cursor: pointer; 
        transition: background-color 0.3s ease;  
        width: 210px;  
        margin: 10px; 
    }

   
    .cancel-button {
        background-color: #6c757d;
    }

    .cancel-button:hover {
        background-color: #5a6268;
    }

    .print-button:hover {
        background-color: #0056b3;
    }

    
    @media print {
    
        .print-button, .cancel-button {
            display: none;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Inter, sans-serif;
            font-weight: 200;
        }

        .qr-codes-to-print {
            display: grid;
            grid-template-columns: repeat(4, 1fr); 
            gap: 10px;
            width: 100%;
            padding: 10px;
        }

        .qr-item {
            text-align: center;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .qr-item img {
            width: 100%;
            height: auto;
            max-width: 150px; 
        }
    }
</style>
