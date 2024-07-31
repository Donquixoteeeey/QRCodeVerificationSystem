<?php
include 'db_connect.php'; // Ensure the path is correct

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM user_info WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: user_info.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
