<?php
include "../db.php";
$create = "CREATE TABLE IF NOT EXISTS services (
  service_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) DEFAULT 0.00,
  description VARCHAR(255)
)";
mysqli_query($conn, $create);
$result = mysqli_query($conn, "SELECT * FROM services ORDER BY service_id DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<?php include "../nav.php"; ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold"><i class="bi bi-briefcase-fill me-2"></i>Services</h2>
    <div class="d-flex gap-2">
      <a href="services_edit.php" class="btn btn-success rounded-pill shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Service
      </a>
      <a href="../index.php" class="btn btn-outline-secondary rounded-pill shadow-sm">
        <i class="bi bi-house-door me-1"></i> Home
      </a>
    </div>
  </div>
  <div class="card shadow border-0 rounded-3">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle">
          <thead class="table-dark">
            <tr>
              <th class="py-3 ps-4">ID</th>
              <th class="py-3">Name</th>
              <th class="py-3">Price</th>
              <th class="py-3">Description</th>
              <th class="py-3 pe-4 text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
              <tr>
                <td class="ps-4 text-muted">#<?php echo $row['service_id']; ?></td>
                <td class="fw-semibold"><?php echo $row['service_name']; ?></td>
                <td><?php echo number_format((float)$row['price'], 2); ?></td>
                <td><?php echo $row['description']; ?></td>
                <td class="pe-4 text-end">
                  <a href="services_edit.php?id=<?php echo $row['service_id']; ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                </td>
              </tr>
            <?php } ?>
            <?php if(mysqli_num_rows($result) == 0): ?>
              <tr>
                <td colspan="5" class="text-center py-5 text-muted">No services found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
