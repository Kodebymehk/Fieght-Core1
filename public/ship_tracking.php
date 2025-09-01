<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipment Tracking | CORE 1</title>
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
    <a href="shipment.php">🚚 Shipment Booking & Routing</a>
    <a href="consolidation.php">📦 Consolidation</a>
    <a href="hmb.php">📄 BL Generator</a>
    <a href="ship_tracking.php" class="active">🛰 Tracking</a>
    <a href="archives.php">🗃 Archives</a>
  </div>

  <div class="content" id="mainContent">
    <div class="header">
      <div class="hamburger" id="hamburger">☰</div>
      <div>
        <h1>Shipment Tracking <span class="system-title">| CORE 1</span></h1>
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

    <!-- CONTENT -->
    <div class="container mt-4">
      <h3>Shipment Tracking</h3>
      <table class="table table-striped table-bordered" id="trackingTable">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Shipment ID</th>
            <th>Location</th>
            <th>Status</th>
            <th>Last Update</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <script>
      document.getElementById('themeToggle').addEventListener('change', () => {
        document.body.classList.toggle('dark-mode', event.target.checked);
      });
      document.getElementById('hamburger').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainContent').classList.toggle('expanded');
      });
      fetch('../api/ship_tracking.php')
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#trackingTable tbody');
          tbody.innerHTML = '';
          data.forEach(t => {
            tbody.innerHTML += `
        <tr>
          <td>${t.id}</td>
          <td>${t.shipment_id}</td>
          <td>${t.location}</td>
          <td>${t.status}</td>
          <td>${t.updated_at}</td>
        </tr>
      `;
          });
        })
        .catch(err => console.error('Error fetching tracking:', err));
    </script>

</body>

</html>