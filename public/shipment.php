<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipment Booking | CORE 1</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
  <div class="sidebar" id="sidebar">
    <div class="logo"><img src="../assets/logo.png" alt="SLATE Logo"></div>
    <div class="system-name"><strong>CORE 1</strong></div>
    <a href="../index.php">📊 Dashboard</a>
    <a href="purchase_orders.php">📝 Purchase Orders</a>
    <a href="shipment.php" class="active">🚚 Shipment Booking & Routing</a>
    <a href="consolidation.php">📦 Consolidation</a>
    <a href="hmb.php">📄 BL Generator</a>
    <a href="ship_tracking.php">🛰 Tracking</a>
    <a href="archives.php">🗃 Archives</a>
  </div>

  <div class="content" id="mainContent">
    <div class="header">
      <div class="hamburger" id="hamburger">☰</div>
      <div>
        <h1>Shipment Booking <span class="system-title">| CORE 1</span></h1>
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
      <h2>Shipment Booking</h2>

      <!-- Create Shipment Form -->
      <div class="card mb-3">
        <div class="card-header">Create Shipment</div>
        <div class="card-body">
          <form id="shipmentForm">
            <div class="mb-2">
              <label class="form-label">Select PO</label>
              <select class="form-select" name="po_id" id="poSelect" required></select>
            </div>
            <div class="mb-2">
              <input type="text" class="form-control" name="driver_name" placeholder="Driver Name">
            </div>
            <div class="mb-2">
              <input type="text" class="form-control" name="vehicle_number" placeholder="Vehicle Number">
            </div>
            <div class="mb-2">
              <select class="form-select" name="status" hidden>
                <option value="Ready" selected>Ready</option>
              </select>

            </div>
            <button type="submit" class="btn btn-primary">Create Shipment</button>
          </form>
        </div>
      </div>

      <!-- Shipment Table -->
      <h3>All Shipments</h3>
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>PO</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Cargo</th>
            <th>Driver</th>
            <th>Vehicle</th>
            <th>Status</th>
            <th>Consolidated</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="shipmentTable"></tbody>
      </table>
    </div>

    <script>
      // Theme toggle
      document.getElementById('themeToggle').addEventListener('change', (event) => {
        document.body.classList.toggle('dark-mode', event.target.checked);
      });

      // Sidebar toggle
      document.getElementById('hamburger').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainContent').classList.toggle('expanded');
      });

      async function archiveShipment(id) {
        if (!confirm("Are you sure you want to archive this shipment?")) return;

        const formData = new FormData();
        formData.append("id", id);

        const res = await fetch('../api/shipment.php?action=archive', {
          method: 'POST',
          body: formData
        });
        const result = await res.json();
        alert(result.message);
        if (result.success) fetchShipments();
      }

      async function updateStatus(id, status) {
        const formData = new FormData();
        formData.append("id", id);
        formData.append("status", status);

        const res = await fetch('../api/shipment.php?action=updateStatus', {
          method: 'POST',
          body: formData
        });
        const result = await res.json();
        alert(result.message);
        if (result.success) fetchShipments();
      }


      async function fetchPOs() {
        const res = await fetch('../api/purchase_orders.php?action=list');
        const data = await res.json();
        const poSelect = document.getElementById('poSelect');
        poSelect.innerHTML = '<option value="">Select...</option>';
        data.forEach(po => {
          poSelect.innerHTML += `<option value="${po.id}">${po.po_number} (${po.origin} → ${po.destination})</option>`;
        });
      }

      async function fetchShipments() {
        const res = await fetch('../api/shipment.php?action=list');
        const data = await res.json();
        const tbody = document.getElementById('shipmentTable');
        tbody.innerHTML = '';
        data.forEach(s => {
          tbody.innerHTML += `
  <tr>
    <td>${s.id}</td>
    <td>${s.po_number}</td>
    <td>${s.origin}</td>
    <td>${s.destination}</td>
    <td>${s.cargo_info ?? ''}</td>
    <td>${s.driver_name ?? ''}</td>
    <td>${s.vehicle_number ?? ''}</td>
    <td>${s.status}</td>
    <td>${s.consolidated == 1 ? 'Yes' : 'No'}</td>
    <td>
  ${s.status === "Ready" ? `<button class="btn btn-sm btn-warning" onclick="updateStatus(${s.id}, 'In Transit')">Set Transit</button>` : ''}
  ${s.status === "In Transit" ? `<button class="btn btn-sm btn-success" onclick="updateStatus(${s.id}, 'Delivered')">Set Delivered</button>` : ''}
  ${s.status === "Delivered" ? `<button class="btn btn-sm btn-secondary" onclick="archiveShipment(${s.id})">Archive</button>` : ''}
</td>

  </tr>`;

        });
      }

      document.getElementById('shipmentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const res = await fetch('../api/shipment.php?action=create', {
          method: 'POST',
          body: formData
        });
        const result = await res.json();
        alert(result.message);
        if (result.success) {
          this.reset();
          fetchShipments();
        }
      });

      fetchPOs();
      fetchShipments();
    </script>
</body>

</html>