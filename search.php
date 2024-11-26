<?php
include 'db_connect.php';

$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

if ($searchTerm === '') {
    $sql = "SELECT id, name, vehicle, plate_number, contact_number FROM user_info";
} else {
    $sql = "SELECT id, name, vehicle, plate_number, contact_number FROM user_info WHERE name LIKE ?";
}

$stmt = $conn->prepare($sql);

if ($searchTerm !== '') {
    $searchTerm = "%{$searchTerm}%";
    $stmt->bind_param("s", $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr onclick=\"window.location='user_detail.php?id={$row['id']}'\">
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
    $output .= "<tr><td colspan='5'>No records found</td></tr>";
}

echo $output;
?>
