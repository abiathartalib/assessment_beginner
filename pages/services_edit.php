<?php
include "../includes/auth.php";
include "../db.php";

$id = $_GET['id'];
$get = mysqli_query($conn, "SELECT * FROM services WHERE service_id = $id");
$service = mysqli_fetch_assoc($get);

$message = "";
$messageType = "";

if (!$service) {
    $message = "Service not found.";
    $messageType = "danger";
} else {
    if (isset($_POST['update'])) {
        $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
        $price = $_POST['price'] !== "" ? (float) $_POST['price'] : 0;
        $description = mysqli_real_escape_string($conn, $_POST['description']);

        if ($service_name == "") {
            $message = "Service name is required.";
            $messageType = "danger";
        } else {
            $sql = "UPDATE services SET service_name='$service_name', price=$price, description='$description' WHERE service_id = $id";
            if (mysqli_query($conn, $sql)) {
                $message = "Service updated successfully.";
                $messageType = "success";
                $get = mysqli_query($conn, "SELECT * FROM services WHERE service_id = $id");
                $service = mysqli_fetch_assoc($get);
            } else {
                $message = "Error updating service: " . mysqli_error($conn);
                $messageType = "danger";
            }
        }
    }
}

$path_to_root = "../";
$page = "services";
$page_title = "Edit Service";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Service</h5>
                <a href="services_list.php" class="btn btn-sm btn-outline-secondary">Back to Services</a>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>

                <?php if ($service): ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input type="text" name="service_name" class="form-control" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($service['price']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($service['description']); ?></textarea>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update Service</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
