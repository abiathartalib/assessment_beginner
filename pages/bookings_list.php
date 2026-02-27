<?php
include "../includes/auth.php";
include "../db.php";

$message = "";
$messageType = "";

if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    if ($delete_id > 0) {
        if (mysqli_query($conn, "DELETE FROM bookings WHERE booking_id = $delete_id")) {
            $message = "Booking deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Error deleting booking: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$sql = "SELECT b.*, c.full_name, s.service_name 
        FROM bookings b
        LEFT JOIN clients c ON b.client_id = c.client_id
        LEFT JOIN services s ON b.service_id = s.service_id
        ORDER BY b.booking_id DESC";
$result = mysqli_query($conn, $sql);

$path_to_root = "../";
$page = "bookings";
$page_title = "Bookings";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Bookings</h4>
        <a href="bookings_create.php" class="btn btn-primary">Create Booking</a>
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
                                <th>Client</th>
                                <th>Service</th>
                                <th>Booking Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['booking_id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                        <td><?php echo $row['booking_date'] ?? ""; ?></td>
                                        <td><?php echo number_format((float)($row['total_amount'] ?? 0), 2); ?></td>
                                        <td><?php echo htmlspecialchars($row['status'] ?? ""); ?></td>
                                        <td class="text-end">
                                            <a href="bookings_create.php?client_id=<?php echo $row['client_id']; ?>&service_id=<?php echo $row['service_id']; ?>" class="btn btn-sm btn-outline-secondary">Duplicate</a>
                                            <a href="bookings_list.php?delete_id=<?php echo $row['booking_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this booking?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No bookings found.</td>
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
