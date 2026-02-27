<?php
include "../includes/auth.php";
include "../db.php";

$message = "";
$messageType = "";

if (isset($_POST['save'])) {
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $price = $_POST['price'] !== "" ? (float) $_POST['price'] : 0;
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if ($service_name == "") {
        $message = "Service name is required.";
        $messageType = "danger";
    } else {
        $sql = "INSERT INTO services (service_name, price, description) VALUES ('$service_name', $price, '$description')";
        if (mysqli_query($conn, $sql)) {
            $message = "Service added successfully.";
            $messageType = "success";
        } else {
            $message = "Error adding service: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$path_to_root = "../";
$page = "services";
$page_title = "Add Service";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Service</h5>
                <a href="services_list.php" class="btn btn-sm btn-outline-secondary">Back to Services</a>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="service_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">Save Service</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
