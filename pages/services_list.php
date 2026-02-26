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

$path_to_root = "../";
$page = "services";
$page_title = "Services";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
  <div>
    <h2 class="fw-bold mb-1">Services</h2>
    <p class="text-muted mb-0">Manage your service offerings</p>
  </div>
  <a href="services_edit.php" class="btn btn-primary shadow-sm">
    <i class="bi bi-plus-lg me-1"></i> Add Service
  </a>
</div>

<div class="card shadow-sm border-0 fade-in" style="animation-delay: 0.1s;">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead>
          <tr>
            <th class="py-3 ps-4" style="width: 10%;">ID</th>
            <th class="py-3" style="width: 25%;">Service Name</th>
            <th class="py-3" style="width: 15%;">Price</th>
            <th class="py-3" style="width: 35%;">Description</th>
            <th class="py-3 pe-4 text-end" style="width: 15%;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td class="ps-4 text-muted">#<?php echo $row['service_id']; ?></td>
              <td class="fw-semibold text-dark">
                <div class="d-flex align-items-center">
                    <div class="rounded bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; font-size: 0.9rem;">
                        <i class="bi bi-tag-fill"></i>
                    </div>
                    <?php echo $row['service_name']; ?>
                </div>
              </td>
              <td class="fw-bold text-dark">$<?php echo number_format((float)$row['price'], 2); ?></td>
              <td class="text-secondary text-truncate" style="max-width: 200px;"><?php echo $row['description']; ?></td>
              <td class="pe-4 text-end">
                <a href="services_edit.php?id=<?php echo $row['service_id']; ?>" class="btn btn-sm btn-light text-primary">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
              </td>
            </tr>
          <?php } ?>
          <?php if(mysqli_num_rows($result) == 0): ?>
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-briefcase fs-1 mb-3 text-secondary opacity-50"></i>
                    <p class="mb-0">No services found.</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
