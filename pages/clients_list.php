<?php
include "../includes/auth.php";
include "../db.php";
$message = "";
$messageType = "";

if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    if ($delete_id > 0) {
        if (mysqli_query($conn, "DELETE FROM clients WHERE client_id = $delete_id")) {
            $message = "Client deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Error deleting client: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM clients ORDER BY client_id DESC");

$path_to_root = "../";
$page = "clients";
$page_title = "Clients";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Clients</h4>
        <a href="clients_add.php" class="btn btn-primary">Add Client</a>
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
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['client_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                                    <td><?php echo $row['created_at'] ?? ""; ?></td>
                                    <td class="text-end">
                                        <a href="clients_edit.php?id=<?php echo $row['client_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="clients_list.php?delete_id=<?php echo $row['client_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this client?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No clients found.</td>
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
