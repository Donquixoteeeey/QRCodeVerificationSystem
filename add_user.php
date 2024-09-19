<?php
include 'db_connect.php';

$name = $vehicle = $plate_number = $contact_number = "";
$name_err = $vehicle_err = $plate_number_err = $contact_number_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the user's name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["vehicle"]))) {
        $vehicle_err = "Please enter the vehicle.";
    } else {
        $vehicle = trim($_POST["vehicle"]);
    }

    if (empty(trim($_POST["plate_number"]))) {
        $plate_number_err = "Please enter the plate number.";
    } else {
        $plate_number = trim($_POST["plate_number"]);
    }

    if (empty(trim($_POST["contact_number"]))) {
        $contact_number_err = "Please enter the contact number.";
    } else {
        $contact_number = trim($_POST["contact_number"]);
    }

    if (empty($name_err) && empty($vehicle_err) && empty($plate_number_err) && empty($contact_number_err)) {
        $sql = "INSERT INTO user_info (name, vehicle, plate_number, contact_number) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $param_name, $param_vehicle, $param_plate_number, $param_contact_number);

            $param_name = $name;
            $param_vehicle = $vehicle;
            $param_plate_number = $plate_number;
            $param_contact_number = $contact_number;

            if ($stmt->execute()) {
                header("location: user_info.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-family: 'Comfortaa', cursive;
            color: #333;
            text-align: center;
            color: #2C2B6D;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 15px;
            font-size: 16px;
            box-sizing: border-box;
            margin-top: 10px;
        }

        .form-group .error {
            color: #dc3545;
            font-size: 14px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border-radius: 15px;
            background-color: #2C2B6D;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn + .btn {
            background-color: #6c757d;
        }

        .btn + .btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="vehicle">Vehicle</label>
                <input type="text" name="vehicle" id="vehicle" value="<?php echo htmlspecialchars($vehicle); ?>">
                <span class="error"><?php echo $vehicle_err; ?></span>
            </div>
            <div class="form-group">
                <label for="plate_number">Plate Number</label>
                <input type="text" name="plate_number" id="plate_number" value="<?php echo htmlspecialchars($plate_number); ?>">
                <span class="error"><?php echo $plate_number_err; ?></span>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>">
                <span class="error"><?php echo $contact_number_err; ?></span>
            </div>
            <div class="btn-container">
                <input type="submit" class="btn" value="Submit">
                <a href="user_info.php" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
