<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Client Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .hero-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 4rem 2rem;
      border-radius: 0.5rem;
      margin-bottom: 2rem;
    }
    .card {
      transition: transform 0.2s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>
<body>

  <div class="container py-5">
    <div class="hero-section text-center shadow">
      <h1 class="display-4 fw-bold">Client Management System</h1>
      <p class="lead">Manage your clients efficiently and effectively.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body text-center p-5">
            <h3 class="card-title mb-3">View Clients</h3>
            <p class="card-text text-muted mb-4">Access the full list of your clients, search, and manage their details.</p>
            <a href="pages/clients_list.php" class="btn btn-primary btn-lg px-4 rounded-pill">Go to List</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body text-center p-5">
            <h3 class="card-title mb-3">Add New Client</h3>
            <p class="card-text text-muted mb-4">Register a new client into the system with their contact information.</p>
            <a href="pages/clients_add.php" class="btn btn-success btn-lg px-4 rounded-pill">Add Client</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body text-center p-5">
            <h3 class="card-title mb-3">Manage Services</h3>
            <p class="card-text text-muted mb-4">View and edit services, pricing and descriptions.</p>
            <a href="pages/services_list.php" class="btn btn-warning btn-lg px-4 rounded-pill">Services</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
