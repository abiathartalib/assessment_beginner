<?php
include "../db.php";

$id = $_GET['id'];
$get = mysqli_query($conn, "SELECT * FROM clients WHERE client_id = $id");
$client = mysqli_fetch_assoc($get);

$message = "";
$messageType = "";

if (isset($_POST['update'])) {
  $full_name = $_POST['full_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  if ($full_name == "" || $email == "") {
    $message = "Name and Email are required!";
    $messageType = "danger";
  } else {
    $sql = "UPDATE clients
            SET full_name='$full_name', email='$email', phone='$phone', address='$address'
            WHERE client_id=$id";
    if(mysqli_query($conn, $sql)) {
        header("Location: clients_list.php");
        exit;
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "danger";
    }
  }
}

$path_to_root = "../";
$page = "clients";
$page_title = "Edit Client";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row justify-content-center fade-in">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow-sm border-0 rounded-3 mt-4">
      <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Client</h5>
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
                <label class="form-label fw-bold text-muted small text-uppercase">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="full_name" class="form-control border-start-0 ps-0" value="<?php echo $client['full_name']; ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-muted small text-uppercase">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0" value="<?php echo $client['email']; ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-muted small text-uppercase">Phone</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                    <input type="text" name="phone" class="form-control border-start-0 ps-0" value="<?php echo $client['phone']; ?>">
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold text-muted small text-uppercase">Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                    <textarea name="address" class="form-control border-start-0 ps-0" rows="3"><?php echo $client['address']; ?></textarea>
                </div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="clients_list.php" class="btn btn-light text-secondary">Cancel</a>
            <button type="submit" name="update" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-check-lg me-1"></i> Update Client
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
