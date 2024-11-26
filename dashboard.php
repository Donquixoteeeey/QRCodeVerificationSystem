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
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out; 
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
    
        .dashboard-cards {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px; 
        }
        
        .card {
            background-color: #ECECEC;
            border-radius: 15px;
            padding: 20px;
            width: 30%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s ease-in-out;
            margin-bottom: 20px; 
        }
        
        .card:hover {
            transform: translateY(-10px);
        }
        
        .card-icon {
            font-size: 25px;
            color: #2C2B6D;
            margin-bottom: 28px;
        }
        
        .card-content h3 {
            font-family: 'Comfortaa', cursive;
            font-size: 18px;
            color: #2C2B6D;
            margin: 0;
            margin-left: 10px;
        }
        
        .card-content p {
            font-size: 24px;
            font-weight: bold;
            color: #000522;
            margin: 0;
            margin-top: 10px;
            margin-left: 10px;
        }
        
        @media (max-width: 768px) {
            .dashboard-cards {
            flex-direction: column;
            align-items: center;
        }

    }
    
    .tables-container {
        display: flex; 
        justify-content: space-between; 
        width: 100%; 
        font-family: 'Inter', sans-serif; 
    }

    .users-table-container {
        font-family: 'Inter', sans-serif;
        width: 97%; 
    margin-top: 10px; 
    background-color: #fff; 
    border-radius: 15px; 
    padding: 20px; 
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
    margin-bottom: 40px; 
    }

    .users-table-container h2 {
        font-family: 'Comfortaa', cursive; 
        color: #2C2B6D; 
        margin-bottom: 20px; 
        margin-top: 10px;
        font-size: 22px; 
    }
    
    .table-container {
        width: 45%; 
        margin-top: 10px; 
        background-color: #fff; 
        border-radius: 15px; 
        padding: 20px; 
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
        margin-bottom: 20px;
    }

    .report-generation-container{
        width: 97%; 
        margin-top: 10px; 
        background-color: #fff; 
        border-radius: 15px; 
        padding: 20px; 
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
        margin-bottom: 20px;
    }
    .report-generation-container h2{
        font-family: 'Comfortaa', cursive; 
        color: #2C2B6D; 
        margin-bottom: 25px; 
        margin-top: 10px;
        font-size: 22px;
    }
    
    .table-container h2 {
        font-family: 'Comfortaa', cursive; 
        color: #2C2B6D; 
        margin-bottom: 20px; 
        margin-top: 10px;
        font-size: 22px; 
    }

    .all-registered-users-table,
    .user-details-table,
    .previously-scanned-table {
        width: 100%; 
        border-collapse: collapse; 
    }
    .all-registered-users-table th,
    .all-registered-users-table td,
    .user-details-table th,
    .user-details-table td,
    .previously-scanned-table th,
    .previously-scanned-table td {
        padding: 10px; 
        text-align: left; 
        border-bottom: 1px solid #ddd; 
        border-top-left-radius: 10px;
        border-top-right-radius: 10px; 
    }
    
    .all-registered-users-table th,
    .user-details-table th,
    .previously-scanned-table th {
        background-color: #2C2B6D; 
        font-weight: bold; 
        color: #f1f1f1;
        font-size: 15px;
    }

    .search-input {
    margin-bottom: 20px;
    padding: 10px;
    width: 98%; 
    border: 1px solid #ddd;
    border-radius: 20px; 
    font-size: 16px; 
    color: #333; 
    
    transition: border-color 0.3s, box-shadow 0.3s;
}

.search-input:focus {
    outline: none; 
    border-color: #2C2B6D; 
    box-shadow: 0 0 5px rgba(44, 43, 109, 0.5); 
}
.table-scroll-container {
    max-height: 300px; 
    overflow-y: auto; 
    overflow-x: hidden; 
    border-radius: 10px; 
    margin-top: 10px;
}
.table-scroll-container::-webkit-scrollbar {
    width: 0; 
    background: transparent; 
}


.table-scroll-container {
    scrollbar-width: none; 
}
.all-registered-users-table {
    width: 100%;
    border-collapse: collapse; 
}

.all-registered-users-table th,
.all-registered-users-table td {
    padding: 10px; 
    text-align: left; 
}

.all-registered-users-table thead {
    position: sticky; 
    top: 0; 
    background-color: #2C2B6D; 
    color: #f1f1f1; 
    z-index: 10;
    
}

.all-registered-users-table tbody tr:hover {
    background-color: #f0f0f0;
    cursor: pointer; 
    border-radius: 0;
}


.report-generation-container {
    width: 97%;
    margin-top: 10px;
    background-color: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}


.report-generation-container {
    width: 97%;
    margin-top: 10px;
    background-color: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.report-generation-container h2 {
    font-family: 'Comfortaa', cursive;
    color: #2C2B6D;
    margin-bottom: 20px;
    margin-top: 10px;
    font-size: 22px;
}

.date-picker-container {
    display: flex;
    align-items: center; 
    gap: 10px; 
    margin-top: 20px;
    margin-bottom: 10px;
}


.date-picker-container label {
    font-family: 'Inter', sans-serif;
    color: #333;
    font-size: 16px;
    margin-bottom: 0;
    margin-left: 10px; 
}


.date-input {
    width: 20%;
    padding: 5px;
    font-size: 15px;
    border-radius: 15px;
    border: 1px solid #ccc;
    margin-bottom: 0;
    font-family: Inter, sans-serif;
    margin-left: 10px;
}


.generate-report-btn {
    width: 150px;
    padding: 7px;
    background-color: #2C2B6D;
    color: white;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    font-size: 15px;
    margin-left: 10px;
}

.generate-report-btn:hover {
    background-color: #0056b3;
}


.export-print-container {
    margin-top: 20px;
    display: flex;
    gap: 15px;
    font-family: 'Inter', sans-serif;
    color: #333;
    font-size: 16px;
    margin-bottom: 20px;
    margin-left: 10px; 
}




.export-dropdown, .export-btn, .print-btn {
    width: 140px;
    padding: 5px;
    font-size: 15px;
    border-radius: 15px;
    border: 1px solid #ccc;
    margin-left: 10px;
}

.export-btn, .print-btn {
    background-color: #2C2B6D;
    color: white;
    cursor: pointer;
}

.export-btn:hover, .print-btn:hover {
    background-color: #0056b3;
}

#report-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

#report-table th, #report-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

#report-table th {
    background-color: #2c2b6d;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
}

#report-table tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

#report-table tbody tr:nth-child(even) {
    background-color: #ffffff;
}

#report-table tbody tr:hover {
    background-color: #e0e0e0;
    cursor: pointer;
}


    </style>

</head>

<body>

    <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>
    
    <div class="sidebar">
        <div class="logo-container">
            <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Admin Dashboard Logo">
        </div>
        <a href="dashboard.php" class="highlighted"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php"><i class="fas fa-users"></i> User Information</a>
        <a href="qr_code_management.php" ><i class="fas fa-qrcode"></i> QR Code Management</a>
        <a href="activity_logs.php"><i class="fas fa-clipboard-list"></i> Activity Logs</a>
        <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
    <div class="dashboard-title">Dashboard</div>
    <div class="date-display"></div>

    <div class="dashboard-cards">
   
    <div class="card">
        <div class="card-icon"><i class="fas fa-users"></i></div>
        <div class="card-content">
            <h3>Registered Users</h3>
            <p id="total-users">Loading...</p> 
        </div>
    </div>

    <div class="card">
        <div class="card-icon"><i class="fas fa-user-plus"></i></div> 
        <div class="card-content">
            <h3>New Users</h3>
            <p id="new-users-weekly">Loading...</p> 
        </div>
    </div>

<div class="card">
    <div class="card-icon"><i class="fas fa-qrcode"></i></div> 
    <div class="card-content">
        <h3>Total Scans Today</h3>
        <p id="totalScansToday">Loading...</p> 
    </div>
</div>
    </div>

    <div class="tables-container">
    <div class="table-container">
        <h2>New Users</h2> 
        <table class="user-details-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Vehicle</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody id="user-details-body">
                <!-- User data will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <div class="table-container">
    <h2>Previously Scanned</h2> 
    <table class="previously-scanned-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Date/Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="previously-scanned-body">
            <!-- User data will be dynamically added here -->
        </tbody>
    </table>
</div>

</div>

<div class="users-table-container">
    <h2>View User's Activity</h2> 
    <input type="text" id="search-input" placeholder="Search by Name..." class="search-input">
    <div class="table-scroll-container"> 
        <table class="all-registered-users-table">
            <thead>
                <tr>
                <th>User ID</th>
        <th>Name</th>
        <th>Vehicle</th>
        <th>Plate Number</th>
                </tr>
            </thead>
            <tbody id="all-registered-body">
                <!-- User data will be dynamically added here -->
            </tbody>
        </table>
    </div>
</div>

<div class="report-generation-container">
    <h2>Generate Activity Report</h2>
   
    <div class="date-picker-container">
        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date" class="date-input" name="start-date">
        
        <label for="end-date">End Date:</label>
        <input type="date" id="end-date" class="date-input" name="end-date">
        
       
        <button class="generate-report-btn" onclick="generateReport()">Generate Report</button>
    </div>
    
   
    <div class="export-print-container">
        <label for="export-format">Select Export Format:</label>
        <select id="export-format" class="export-dropdown">
            <option value="pdf">PDF</option>
            <option value="csv">CSV</option>
        </select>
        
        <button class="export-btn" onclick="handleExport()">Export</button>
        <button class="print-btn" onclick="printReport()">Print</button>
    </div>
    
   
    <div class="report-container">
        <table id="report-table" style="display:none;">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Vehicle</th>
                    <th>Plate Number</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="report-table-body"></tbody>
        </table>
    </div>
</div>




<div style="height: 40px;"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script>
   function generateReport() {
   
    console.log("Report generated!");

    document.getElementById("report-table").style.display = "table";
}

function generateReport() {
    
    console.log("Report generated!");
   
    document.getElementById("report-table").style.display = "table";
}

function handleExport() {
    var selectedFormat = document.getElementById('export-format').value;
    var startDate = document.getElementById('start-date').value || 'Not_specified';
    var endDate = document.getElementById('end-date').value || 'Not_specified';

    if (selectedFormat === 'pdf') {
        exportToPDF();  
    } else if (selectedFormat === 'csv') {
        exportToCSV(startDate, endDate); 
    }
}

function exportToCSV(startDate, endDate) {
    var table = document.getElementById('report-table');
    var rows = table.getElementsByTagName('tr');
    var csvContent = '';

    
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cols = row.getElementsByTagName('td');
        var rowData = [];

      
        for (var j = 0; j < cols.length; j++) {
            rowData.push(cols[j].textContent || cols[j].innerText);
        }

        csvContent += rowData.join(',') + '\n'; 
    }

   
    var filename = 'activity_report_' + startDate + '_to_' + endDate + '.csv';

    
    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename; 
    link.click();
}


function exportToPDF() {
    try {
        
        if (!window.jspdf || !window.jspdf.jsPDF) {
            throw new Error("jsPDF library is not loaded.");
        }

        const { jsPDF } = window.jspdf;
        let pdf = new jsPDF('p', 'pt', 'a4');

        let logoUrl = 'img/CPC%20LOGO%20BACKROUND%20REMOVED.png';
        let logoWidth = 100; 
        let logoHeight = 100; 
        let text = "COLEGIO DE LA PURISIMA CONCEPCION";

        let pageWidth = pdf.internal.pageSize.width;
        let logoX = (pageWidth - logoWidth) / 2; 

        
        pdf.addImage(logoUrl, 'PNG', logoX, 60, logoWidth, logoHeight);

     
        pdf.setFontSize(12);
        let textWidth = pdf.getTextWidth(text);
        let textX = (pageWidth - textWidth) / 2; 
        pdf.text(text, textX, 180); 

        pdf.setFontSize(18);
        let reportTitle = "ACTIVITY REPORT";
        let reportTitleWidth = pdf.getTextWidth(reportTitle); 
        let reportTitleX = (pageWidth - reportTitleWidth) / 2; 
        pdf.text(reportTitle, reportTitleX, 220); 

        let startDate = document.getElementById('start-date').value || 'Not specified';
        let endDate = document.getElementById('end-date').value || 'Not specified';
        pdf.setFontSize(12);
        let dateRange = "Date Range: " + startDate + ' to ' + endDate;
        let dateRangeWidth = pdf.getTextWidth(dateRange);
        let dateRangeX = (pageWidth - dateRangeWidth) / 2; 
        pdf.text(dateRange, dateRangeX, 250); 

        pdf.autoTable({
            html: '#report-table',
            startY: 280, 
            theme: 'grid', 
            headStyles: {
                fillColor: [44, 43, 109],
                textColor: 255,
                fontSize: 10,
                fontStyle: 'bold'
            },
            styles: {
                fontSize: 10,
                cellPadding: 5,
                lineWidth: 0.5,
                lineColor: [0, 0, 0], 
                textColor: [0, 0, 0] 
            },
            margin: { top: 280 }, 
        });

    
        pdf.save("activity_report.pdf");

    } catch (error) {
        console.error("Error generating PDF:", error);
        alert("An error occurred while generating the PDF. Check the console for more details.");
    }
}

function printReport(startDate, endDate) {
    startDate = startDate || 'Not specified';
    endDate = endDate || 'Not specified';

    var reportTable = document.getElementById("report-table");

    var printWindow = window.open('', '', 'height=600,width=800');

    printWindow.document.write('<html><head><title>Activity Report</title>');
    printWindow.document.write('<style> body { font-family: Inter, sans-serif; padding: 20px; } table { width: 100%; border-collapse: collapse; } th, td { padding: 10px; border: 1px solid #ddd; text-align: left; } th { background-color: #2C2B6D; color: white; } h2 { font-weight: 300; margin-top: 20px; }</style></head><body>');
    
  
    printWindow.document.write('<div style="text-align: center;">');
    printWindow.document.write('<img src="img/CPC%20LOGO%20BACKROUND%20REMOVED.png" alt="Report Image" style="width: 100px; height: 100px;">');
    printWindow.document.write('<p><strong>COLEGIO DE LA PURISIMA CONCEPCION</strong></p>');  
    printWindow.document.write('<h2><strong>ACTIVITY REPORT</strong></h2>');  
    
    printWindow.document.write('</div>');
    
    
    printWindow.document.write('<p><strong>Date Range:</strong> ' + startDate + ' to ' + endDate + '</p>');

    printWindow.document.write(reportTable.outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}



    function generateReport() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (!startDate || !endDate) {
        alert("Please select both start and end dates.");
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
        alert("Start date cannot be later than end date.");
        return;
    }

  
    fetch('generate_report.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ start_date: startDate, end_date: endDate })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); 
        const reportTable = document.getElementById('report-table');
        const reportTableBody = document.getElementById('report-table-body');

   
        reportTableBody.innerHTML = '';

        if (data.error) {
            
            reportTableBody.innerHTML = `<tr><td colspan="6">${data.error}</td></tr>`;
            reportTable.style.display = 'table';
        } else if (data.length > 0) {
           
            data.forEach(row => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${row.user_id}</td>
                    <td>${row.name}</td>
                    <td>${row.vehicle}</td>
                    <td>${row.plate_number}</td>
                    <td>${row.time_timestamp}</td>
                    <td>${row.action_type}</td>
                `;
                reportTableBody.appendChild(newRow);
            });

          
            reportTable.style.display = 'table';
        } else {
          
            reportTableBody.innerHTML = '<tr><td colspan="6">No activities found for the selected date range.</td></tr>';
            reportTable.style.display = 'table';
        }
    })
    .catch(error => {
        console.error('Error fetching report data:', error);
        alert("An error occurred while generating the report.");
    });
}


    
document.addEventListener('DOMContentLoaded', function() {
    fetch('get_all_users.php')  
        .then(response => response.json())
        .then(data => {
            const allRegisteredBody = document.getElementById('all-registered-body');
            allRegisteredBody.innerHTML = ''; 

            
            const users = data;

           
            function renderUsers(usersToRender) {
    allRegisteredBody.innerHTML = '';
    usersToRender.forEach(user => {
        const row = document.createElement('tr');

        row.addEventListener('click', () => {
            window.location.href = `user_activities.php?user_id=${user.id}`;
        });

        
        row.innerHTML = `
            <td>${user.id}</td> <!-- User ID added here -->
            <td>${user.name}</td>
            <td>${user.vehicle}</td>
            <td>${user.plate_number}</td>
        `;

        allRegisteredBody.appendChild(row);
    });
}


            
            renderUsers(users);

            
            const searchInput = document.getElementById('search-input');
            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();
                const filteredUsers = users.filter(user => 
                    user.name.toLowerCase().includes(searchTerm)
                );
                renderUsers(filteredUsers);
            });
        })
        .catch(error => console.error('Error fetching all registered users:', error));
});


document.addEventListener('DOMContentLoaded', function() {
    fetch('get_recent_scans.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);  

            const previouslyScannedBody = document.getElementById('previously-scanned-body');
            previouslyScannedBody.innerHTML = '';

            data.forEach(scan => {
                const row = document.createElement('tr');
                const time = new Date(scan.time_timestamp).toLocaleString(); 
                
                row.innerHTML = `
                    <td>${scan.user_id}</td>
                    <td>${time}</td>
                    <td>${scan.action_type}</td>
                `;
                previouslyScannedBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching recent scans:', error));
});

document.addEventListener('DOMContentLoaded', function() {
    fetch('get_new_users.php') 
        .then(response => response.json())
        .then(data => {
            const userDetailsBody = document.getElementById('user-details-body');
            userDetailsBody.innerHTML = ''; 

            data.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.name}</td>
                    <td>${user.vehicle}</td>
                    <td>${new Date(user.registration_date).toLocaleDateString()}</td>
                `;
                userDetailsBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching new users:', error));
});

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
           
           document.addEventListener('DOMContentLoaded', function() {
            fetch('get_total_users.php') 
            .then(response => response.text())
            .then(data => {
                document.querySelector('#total-users').textContent = data;
            })
            .catch(error => console.error('Error:', error));
    });

    fetch('get_new_users_weekly.php')  
    .then(response => response.text())
    .then(data => {
        document.getElementById('new-users-weekly').textContent = data;
    })
    .catch(error => console.error('Error fetching new users:', error));
    
    fetch('get_total_scans.php')
    .then(response => response.json())
    .then(data => {
        const totalScansTodayElement = document.getElementById('totalScansToday');
        totalScansTodayElement.textContent = data.total_scans_today;
    })
    .catch(error => console.error('Error fetching total scans today:', error));

</script>

</body>

</html>
