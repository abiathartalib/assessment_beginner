<?php
include "../db.php";

$message = "";
$messageType = "";

if (isset($_POST['save'])) {
  $full_name = $_POST['full_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  if ($full_name == "" || $email == "") {
    $message = "Name and Email are required!";
    $messageType = "danger";
  } else {
    $sql = "INSERT INTO clients (full_name, email, phone, address)
            VALUES ('$full_name', '$email', '$phone', '$address')";
    if(mysqli_query($conn, $sql)) {
        header("Location: clients_list.php");
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
  <title>Add Client</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php include "../nav.php"; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow border-0 rounded-3 mt-4">
        <div class="card-header bg-success text-white py-3 rounded-top-3">
          <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Add New Client</h4>
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
              <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" class="form-control" placeholder="Enter full name" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Phone</label>
              <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Address</label>
              <textarea name="address" class="form-control" rows="3" placeholder="Enter address"></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <a href="clients_list.php" class="btn btn-secondary me-md-2">Cancel</a>
              <button type="submit" name="save" class="btn btn-success px-4">Save Client</button>
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
