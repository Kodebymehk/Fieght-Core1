<?php
// add_purchase_order.php
include 'db.php'; // make sure this has your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $po_number   = $_POST['po_number'];
    $supplier    = $_POST['supplier'];
    $order_date  = $_POST['order_date'];
    $origin      = $_POST['origin'];
    $destination = $_POST['destination'];
    $cargo_info  = $_POST['cargo_info'];
    $status      = "Pending"; // default when creating

    $sql = "INSERT INTO purchase_orders
            (po_number, supplier, order_date, origin, destination, cargo_info, status)
            VALUES
            ('$po_number', '$supplier', '$order_date', '$origin', '$destination', '$cargo_info', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Purchase Order created successfully!');
                window.location.href = '../public/user_po.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
