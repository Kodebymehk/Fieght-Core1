<?php
header("Content-Type: application/json");
include 'db.php';

$sql = "SELECT ref_no, type, mode, status, origin, destination, eta
        FROM shipments
        ORDER BY updated_at DESC
        LIMIT 5";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
