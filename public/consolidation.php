<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Consolidation Module</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body class="bg-light">

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="logo"><img src="../assets/logo.png" alt="SLATE Logo"></div>
    <div class="system-name"><strong>CORE 1</strong></div>
    <a href="../index.php">📊 Dashboard</a>
    <a href="purchase_orders.php">📝 Purchase Orders</a>
    <a href="shipment.php">🚚 Shipment Booking & Routing</a>
    <a href="consolidation.php" class="active">📦 Consolidation</a>
    <a href="hmb.php">📄 BL Generator</a>
    <a href="ship_tracking.php">🛰 Tracking</a>
    <a href="archives.php">🗃 Archives</a>
  </div>

  <!-- Main Content -->
  <div class="content" id="mainContent">
    <div class="header">
      <div class="hamburger" id="hamburger">☰</div>
      <div>
        <h1>Consolidation <span class="system-title">| CORE 1</span></h1>
      </div>
      <div class="theme-toggle-container">
        <div class="dropdown">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="bi bi-person"></i></button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="login.html">Logout</a></li>
          </ul>
        </div>
        <span class="theme-label">Dark Mode</span>
        <label class="theme-switch"><input type="checkbox" id="themeToggle"><span class="slider"></span></label>
      </div>
    </div>

    <div class="container mt-4">
      <h3>Suggested Consolidations</h3>
      <form id="consolidationForm">
        <table class="table table-bordered" id="suggestedTable">
          <thead class="table-dark">
            <tr>
              <th>Select</th>
              <th>Shipment ID</th>
              <th>PO Number</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Cargo</th>
              <th>Driver</th>
              <th>Vehicle</th>
              <th>Status</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <button type="submit" class="btn btn-primary">Create Consolidation</button>
      </form>
    </div>

    <script>
      // Fetch shipments (only those not yet consolidated)
      async function fetchShipments() {
        const res = await fetch('../api/consolidation.php?action=list');
        const data = await res.json();
        const tbody = document.querySelector('#suggestedTable tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="10" class="text-center">No shipments available for consolidation</td></tr>`;
          return;
        }

        data.forEach(s => {
          tbody.innerHTML += `
            <tr>
              <td><input type="checkbox" name="shipment_ids[]" value="${s.id}"></td>
              <td>${s.id}</td>
              <td>${s.po_number}</td>
              <td>${s.origin}</td>
              <td>${s.destination}</td>
              <td>${s.cargo_details ?? ''}</td>
              <td>${s.driver_name ?? ''}</td>
              <td>${s.vehicle_number ?? ''}</td>
              <td>${s.status}</td>
              <td>${s.created_at}</td>
            </tr>`;
        });
      }

      // Handle consolidation form submission
      document.getElementById('consolidationForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const shipment_ids = [];
        document.querySelectorAll('input[name="shipment_ids[]"]:checked').forEach(cb => {
          shipment_ids.push(cb.value);
        });

        if (shipment_ids.length === 0) {
          alert("⚠️ Please select at least one shipment");
          return;
        }

        const formData = new FormData();
        shipment_ids.forEach(id => formData.append("shipment_ids[]", id));

        const res = await fetch('../api/consolidation.php?action=create', {
          method: 'POST',
          body: formData
        });

        const result = await res.json();
        alert(result.message);
        fetchShipments(); // Refresh the list after consolidation
      });

      fetchShipments();
    </script>
  </div>

</body>

</html>