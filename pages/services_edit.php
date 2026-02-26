<?php
include "../db.php";
$create = "CREATE TABLE IF NOT EXISTS services (
  service_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) DEFAULT 0.00,
  description VARCHAR(255)
)";
mysqli_query($conn, $create);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$service = ["service_name"=>"", "price"=>"", "description"=>""];
if ($id > 0) {
  $get = mysqli_query($conn, "SELECT * FROM services WHERE service_id = $id");
  if ($get && mysqli_num_rows($get) > 0) {
    $service = mysqli_fetch_assoc($get);
  }
}
$message = "";
$messageType = "";
if (isset($_POST['update']) || isset($_POST['save'])) {
  $service_name = $_POST['service_name'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  if ($service_name == "") {
    $message = "Service name is required!";
    $messageType = "danger";
  } else {
    if ($id > 0 && isset($_POST['update'])) {
      $sql = "UPDATE services SET service_name='$service_name', price='$price', description='$description' WHERE service_id=$id";
    } else {
      $sql = "INSERT INTO services (service_name, price, description) VALUES ('$service_name', '$price', '$description')";
    }
    if(mysqli_query($conn, $sql)) {
        header("Location: services_list.php");
        exit;
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "danger";
    }
  }
}

$path_to_root = "../";
$page = "services";
$page_title = ($id > 0) ? "Edit Service" : "Add Service";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row justify-content-center fade-in">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow-sm border-0 rounded-3 mt-4">
      <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold text-primary">
          <?php if ($id > 0): ?>
            <i class="bi bi-pencil-square me-2"></i>Edit Service
          <?php else: ?>
            <i class="bi bi-plus-square me-2"></i>Add Service
          <?php endif; ?>
        </h5>
      </div>
      <div class="card-body p-4">
        <?php if ($message != ""): ?>
          <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        <form method="post">
          <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-bold text-muted small text-uppercase">Service Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag text-muted"></i></span>
                    <input type="text" name="service_name" class="form-control border-start-0 ps-0" value="<?php echo $service['service_name']; ?>" required>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold text-muted small text-uppercase">Price</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-currency-dollar text-muted"></i></span>
                    <input type="number" step="0.01" name="price" class="form-control border-start-0 ps-0" value="<?php echo $service['price']; ?>">
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold text-muted small text-uppercase">Description</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-text text-muted"></i></span>
                    <textarea name="description" class="form-control border-start-0 ps-0" rows="3"><?php echo $service['description']; ?></textarea>
                </div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="services_list.php" class="btn btn-light text-secondary">Cancel</a>
            <?php if ($id > 0): ?>
              <button type="submit" name="update" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-check-lg me-1"></i> Update Service
              </button>
            <?php else: ?>
              <button type="submit" name="save" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-check-lg me-1"></i> Save Service
              </button>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
