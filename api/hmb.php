<?php
// api/bl.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'db.php';

// Helper to return JSON
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

$action = $_GET['action'] ?? 'list';

// LIST BLs
if ($action === 'list') {
    $sql = "SELECT id, bl_number, type, shipment_id, shipper, consignee, issue_date, status 
            FROM bills_of_lading 
            ORDER BY id DESC";
    $result = $conn->query($sql);
    $bls = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bls[] = [
                "id" => (int)$row["id"],
                "bl_number" => $row["bl_number"],
                "type" => $row["type"] ?? "HBL",
                "shipment_id" => $row["shipment_id"],
                "shipper" => $row["shipper"],
                "consignee" => $row["consignee"],
                "issue_date" => $row["issue_date"] ?? $row["created_at"] ?? null,
                "status" => $row["status"] ?? "DRAFT"
            ];
        }
    }
    jsonResponse($bls);
}

// CREATE BL
if ($action === 'create') {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        jsonResponse(["success" => false, "message" => "POST required"], 405);
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        jsonResponse(["success" => false, "message" => "Invalid JSON"], 400);
    }

    $bl_number = $data["bl_number"] ?? "BL-" . time();

    $stmt = $conn->prepare("INSERT INTO bills_of_lading 
        (bl_number, type, shipment_id, shipper, consignee, origin, destination) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssissss",
        $bl_number,
        $data["type"],
        $data["shipment_id"],
        $data["shipper"],
        $data["consignee"],
        $data["origin"],
        $data["destination"]
    );

    if ($stmt->execute()) {
        jsonResponse([
            "success" => true,
            "message" => "BL created successfully",
            "id" => $stmt->insert_id,
            "bl_number" => $bl_number
        ]);
    } else {
        jsonResponse(["success" => false, "message" => "DB error: " . $stmt->error], 500);
    }
}

jsonResponse(["success" => false, "message" => "Unknown action"], 400);

$conn->close();
