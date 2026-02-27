<?php
include "../includes/auth.php";
include "../db.php";

$create = "CREATE TABLE IF NOT EXISTS services (
  service_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) DEFAULT 0.00,
  description VARCHAR(255)
)";
mysqli_query($conn, $create);

$message = "";
$messageType = "";

if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    if ($delete_id > 0) {
        if (mysqli_query($conn, "DELETE FROM services WHERE service_id = $delete_id")) {
            $message = "Service deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Error deleting service: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM services ORDER BY service_id DESC");

$path_to_root = "../";
$page = "services";
$page_title = "Services";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Services</h4>
        <a href="services_add.php" class="btn btn-primary">Add Service</a>
    </div>
</div>

<?php if ($message != ""): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['service_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                    <td><?php echo number_format($row['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td class="text-end">
                                        <a href="services_edit.php?id=<?php echo $row['service_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="services_list.php?delete_id=<?php echo $row['service_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this service?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No services found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
