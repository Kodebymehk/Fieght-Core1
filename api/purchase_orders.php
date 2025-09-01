<?php
header("Content-Type: application/json");
include 'db.php'; // adjust if needed

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
  // Fetch all POs
  $result = $conn->query("SELECT * FROM purchase_orders ORDER BY id DESC");
  $orders = [];
  while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
  }
  echo json_encode($orders);
  exit;
}

if ($method === "POST") {
  $data = json_decode(file_get_contents("php://input"), true);

  if (!$data) {
    echo json_encode(["error" => "Invalid JSON"]);
    exit;
  }

  $po_number = $data['po_number'] ?? '';
  $supplier = $data['supplier'] ?? '';
  $order_date = $data['order_date'] ?? '';
  $status = $data['status'] ?? '';

  if ($po_number && $supplier && $order_date && $status) {
    $stmt = $conn->prepare("INSERT INTO purchase_orders (po_number, supplier, order_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $po_number, $supplier, $order_date, $status);

    if ($stmt->execute()) {
      echo json_encode(["message" => "PO created successfully"]);
    } else {
      echo json_encode(["error" => "Database insert failed"]);
    }
  } else {
    echo json_encode(["error" => "Missing fields"]);
  }
  exit;
}

echo json_encode(["error" => "Invalid request"]);