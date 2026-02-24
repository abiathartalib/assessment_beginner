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
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo ($id > 0) ? "Edit Service" : "Add Service"; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<?php include "../nav.php"; ?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow border-0 rounded-3 mt-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-3">
          <h4 class="mb-0">
            <?php if ($id > 0): ?>
              <i class="bi bi-pencil-square me-2"></i>Edit Service
            <?php else: ?>
              <i class="bi bi-plus-square me-2"></i>Add Service
            <?php endif; ?>
          </h4>
        </div>
        <div class="card-body p-4">
          <?php if ($message != ""): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
              <?php echo $message; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label class="form-label fw-bold">Service Name <span class="text-danger">*</span></label>
              <input type="text" name="service_name" class="form-control" value="<?php echo $service['service_name']; ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Price</label>
              <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $service['price']; ?>">
            </div>
            <div class="mb-4">
              <label class="form-label fw-bold">Description</label>
              <textarea name="description" class="form-control" rows="3"><?php echo $service['description']; ?></textarea>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <a href="services_list.php" class="btn btn-secondary me-md-2">Cancel</a>
              <?php if ($id > 0): ?>
                <button type="submit" name="update" class="btn btn-primary px-4">Update Service</button>
              <?php else: ?>
                <button type="submit" name="save" class="btn btn-success px-4">Save Service</button>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
