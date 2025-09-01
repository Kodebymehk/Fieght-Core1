<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | CORE 1</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="../assets/logo.png" alt="SLATE Logo">
    </div>
    <div class="system-name"><strong>CORE 1</strong></div>
    <a href="../index.php">📊 Dashboard</a>
    <a href="purchase_orders.php">📝 Purchase Orders</a>
    <a href="shipment.php">🚚 Shipment Booking & Routing</a>
    <a href="consolidation.php">📦 Consolidation</a>
    <a href="hmb.php">📄 BL Generator</a>
    <a href="ship_tracking.php">🛰 Tracking</a>
    <a href="archives.php" class="active">🗃 Archives</a>
  </div>

  <div class="content" id="mainContent">
    <div class="header">
      <div class="hamburger" id="hamburger">☰</div>
      <div>
        <h1>Admin Dashboard <span class="system-title">| CORE 1</span></h1>
      </div>
      <div class="theme-toggle-container">
        <div class="dropdown">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person"></i>
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="login.php">Logout</a></li>
          </ul>
        </div>
        <span class="theme-label">Dark Mode</span>
        <label class="theme-switch">
          <input type="checkbox" id="themeToggle">
          <span class="slider"></span>
        </label>
      </div>
    </div>

    <!-- CONTENT START -->
    <div class="container mt-4">
      <h2 class="mb-3">📦 Archived Shipments</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>PO Number</th>
              <th>Origin</th>
              <th>Destination</th>
              <th>Cargo Info</th>
              <th>Driver</th>
              <th>Vehicle</th>
              <th>Status</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody id="archiveTableBody">
            <tr>
              <td colspan="9" class="text-center">Loading archived shipments...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- CONTENT END -->

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar and Theme Toggle
    document.getElementById('themeToggle').addEventListener('change', function() {
      document.body.classList.toggle('dark-mode', this.checked);
    });

    document.getElementById('hamburger').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('mainContent').classList.toggle('expanded');
    });

    // Fetch Archived Shipments
    async function loadArchives() {
      try {
        const response = await fetch('../api/shipment.php?action=archives');
        const data = await response.json();

        const tbody = document.getElementById('archiveTableBody');
        tbody.innerHTML = "";

        if (data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="9" class="text-center">No archived shipments found.</td></tr>`;
          return;
        }

        data.forEach(row => {
          tbody.innerHTML += `
            <tr>
              <td>${row.id}</td>
              <td>${row.po_number}</td>
              <td>${row.origin}</td>
              <td>${row.destination}</td>
              <td>${row.cargo_info}</td>
              <td>${row.driver_name}</td>
              <td>${row.vehicle_number}</td>
              <td>${row.status}</td>
              <td>${row.created_at}</td>
            </tr>
          `;
        });

      } catch (error) {
        console.error("Error fetching archives:", error);
        document.getElementById('archiveTableBody').innerHTML =
          `<tr><td colspan="9" class="text-center text-danger">Error loading archives.</td></tr>`;
      }
    }

    loadArchives();
  </script>
</body>

</html>
<!-- add action too and unarchive/restore button and delete button -->