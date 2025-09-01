<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BL Generator | CORE 1</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="logo"><img src="../assets/logo.png" alt="SLATE Logo"></div>
    <div class="system-name"><strong>CORE 1</strong></div>
    <a href="../index.php">📊 Dashboard</a>
    <a href="purchase_orders.php">📝 Purchase Orders</a>
    <a href="shipment.php">🚚 Shipment Booking & Routing</a>
    <a href="consolidation.php">📦 Consolidation</a>
    <a href="hmb.php" class="active">📄 BL Generator</a>
    <a href="ship_tracking.php">🛰 Tracking</a>
    <a href="archives.php">🗃 Archives</a>
  </div>

  <!-- Main Content -->
  <div class="content" id="mainContent">
    <div class="header d-flex justify-content-between align-items-center">
      <div class="hamburger" id="hamburger">☰</div>
      <h1>Bill of Lading Generator <span class="system-title">| CORE 1</span></h1>
      <div class="theme-toggle-container d-flex align-items-center">
        <div class="dropdown me-2">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="bi bi-person"></i></button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="login.html">Logout</a></li>
          </ul>
        </div>
        <span class="theme-label me-2">Dark Mode</span>
        <label class="theme-switch">
          <input type="checkbox" id="themeToggle">
          <span class="slider"></span>
        </label>
      </div>
    </div>

    <!-- BL Table & New BL Button -->
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
      <h4 class="mb-0">Bill of Lading (HBL/MBL)</h4>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-circle"></i> New BL
      </button>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle" id="blTable">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>BL Number</th>
                <th>Type</th>
                <th>Shipment</th>
                <th>Consignee</th>
                <th>Shipper</th>
                <th>Issue Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Create BL Modal -->
  <div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <form class="modal-content" id="createForm">
        <div class="modal-header">
          <h5 class="modal-title">Create Bill of Lading</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Type</label>
              <select class="form-select" name="type" required>
                <option value="HBL">HBL (House)</option>
                <option value="MBL">MBL (Master)</option>
              </select>
            </div>
            <div class="col-md-8">
              <label class="form-label">Shipment</label>
              <select class="form-select" name="shipment_id" id="shipmentSelect" required></select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Shipper</label>
              <input class="form-control" name="shipper" placeholder="Shipper name/address">
            </div>
            <div class="col-md-6">
              <label class="form-label">Consignee</label>
              <input class="form-control" name="consignee" placeholder="Consignee name/address">
            </div>
            <div class="col-md-6">
              <label class="form-label">Notify Party</label>
              <input class="form-control" name="notify_party">
            </div>
            <div class="col-md-3">
              <label class="form-label">Issue Place</label>
              <input class="form-control" name="issue_place">
            </div>
            <div class="col-md-3">
              <label class="form-label">Issue Date</label>
              <input type="date" class="form-control" name="issue_date">
            </div>
            <div class="col-md-6">
              <label class="form-label">Port of Loading</label>
              <input class="form-control" name="port_of_loading">
            </div>
            <div class="col-md-6">
              <label class="form-label">Port of Discharge</label>
              <input class="form-control" name="port_of_discharge">
            </div>
            <div class="col-12">
              <label class="form-label">Description of Goods</label>
              <textarea class="form-control" name="description_of_goods" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" type="submit">Create</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const API_BASE = "../api/";

    // Theme toggle
    document.getElementById('themeToggle').addEventListener('change', event => {
      document.body.classList.toggle('dark-mode', event.target.checked);
    });

    // Sidebar toggle
    document.getElementById('hamburger').addEventListener('click', () => {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('mainContent').classList.toggle('expanded');
    });

    // Fetch BLs
    async function fetchBLs() {
      try {
        const res = await fetch(API_BASE + "bl.php?action=list");
        const rows = await res.json();
        const tbody = document.querySelector("#blTable tbody");
        tbody.innerHTML = "";
        rows.forEach((r, i) => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${i+1}</td>
            <td><strong>${r.bl_number}</strong></td>
            <td>${r.type}</td>
            <td>#${r.shipment_id}</td>
            <td>${r.consignee ?? ""}</td>
            <td>${r.shipper ?? ""}</td>
            <td>${r.issue_date ?? ""}</td>
            <td><span class="badge bg-${r.status==='ISSUED'?'success':(r.status==='VOID'?'danger':'secondary')}">${r.status}</span></td>
            <td>
              <a class="btn btn-sm btn-outline-primary" href="bl_view.php?id=${r.id}" target="_blank"><i class="bi bi-printer"></i> Print</a>
            </td>`;
          tbody.appendChild(tr);
        });
      } catch (err) {
        console.error("Error fetching BLs:", err);
      }
    }

    // Fetch shipments for dropdown
    async function fetchShipments() {
      try {
        const res = await fetch(API_BASE + "shipment.php?action=list");
        const data = await res.json();
        const sel = document.getElementById("shipmentSelect");
        sel.innerHTML = "";
        data.forEach(s => {
          const opt = document.createElement("option");
          opt.value = s.id;
          opt.textContent = `#${s.id} • PO ${s.po_number} • ${s.origin} → ${s.destination}`;
          sel.appendChild(opt);
        });
      } catch (err) {
        console.error("Error fetching shipments:", err);
      }
    }

    // Create BL form submit
    document.getElementById("createForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);
      const payload = Object.fromEntries(formData.entries());
      try {
        const res = await fetch(API_BASE + "bl.php?action=create", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        });
        const out = await res.json();
        alert(out.message || (out.success ? "Created" : "Failed"));
        if (out.success) {
          document.querySelector("#createModal .btn-close").click();
          fetchBLs();
        }
      } catch (err) {
        console.error("Error creating BL:", err);
      }
    });

    fetchShipments();
    fetchBLs();
  </script>
</body>
</html>
