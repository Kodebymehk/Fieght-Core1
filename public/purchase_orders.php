<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Purchase Orders | CORE 1</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
  <div class="sidebar" id="sidebar">
    <div class="logo"><img src="../assets/logo.png" alt="SLATE Logo"></div>
    <div class="system-name"><strong>CORE 1</strong></div>
    <a href="../index.php">📊 Dashboard</a>
    <a href="purchase_orders.php" class="active">📝 Purchase Orders</a>
    <a href="shipment.php">🚚 Shipment Booking & Routing</a>
    <a href="consolidation.php">📦 Consolidation</a>
    <a href="hmb.php">📄 BL Generator</a>
    <a href="ship_tracking.php">🛰 Tracking</a>
    <a href="archives.php">🗃 Archives</a>
  </div>

  <div class="content" id="mainContent">
    <div class="header">
      <div class="hamburger" id="hamburger">☰</div>
      <div>
        <h1>Purchase Orders <span class="system-title">| CORE 1</span></h1>
      </div>
      <div class="theme-toggle-container">
        <div class="dropdown">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-person"></i>
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="login.html">Logout</a></li>
          </ul>
        </div>
        <span class="theme-label">Dark Mode</span>
        <label class="theme-switch"><input type="checkbox" id="themeToggle"><span class="slider"></span></label>
      </div>
    </div>

    <!-- CONTENT HERE-->
    <div class="container mt-4">
      <h3>Purchase Orders</h3>
      <table class="table table-striped table-bordered" id="poTable">
        <thead class="table-dark">
          <tr>
            <th>PO Number</th>
            <th>Supplier</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Cargo Info</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <!-- END OF CONTENT -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.getElementById('themeToggle').addEventListener('change', (event) => {
        document.body.classList.toggle('dark-mode', event.target.checked);
      });
      document.getElementById('hamburger').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainContent').classList.toggle('expanded');
      });

      // Fetch purchase orders
      fetch('../api/purchase_orders.php')
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#poTable tbody');
          tbody.innerHTML = '';
          data.forEach(po => {
            tbody.innerHTML += `
          <tr>
            <td>${po.po_number}</td>
            <td>${po.supplier}</td>
            <td>${po.order_date}</td>
            <td>${po.status}</td>
            <td>${po.origin ?? ''}</td>
            <td>${po.destination ?? ''}</td>
            <td>${po.cargo_info ?? ''}</td>
          </tr>`;
          });
        })
        .catch(err => console.error('Error fetching Purchase Orders:', err));
    </script>
</body>

</html>