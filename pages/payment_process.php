<?php
include "../includes/auth.php";
include "../db.php";

$message = "";
$messageType = "";

$bookings_res = mysqli_query($conn, "SELECT booking_id FROM bookings ORDER BY booking_id DESC");

if (isset($_POST['save'])) {
    $booking_id = isset($_POST['booking_id']) && $_POST['booking_id'] !== "" ? (int) $_POST['booking_id'] : null;
    $amount = $_POST['amount'] !== "" ? (float) $_POST['amount'] : 0;
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $status = $_POST['status'] !== "" ? mysqli_real_escape_string($conn, $_POST['status']) : "Completed";

    if ($amount <= 0) {
        $message = "Amount must be greater than zero.";
        $messageType = "danger";
    } else {
        $booking_value = $booking_id !== null ? $booking_id : "NULL";
        $sql = "INSERT INTO payments (booking_id, amount, method, status) VALUES ($booking_value, $amount, '$method', '$status')";
        if (mysqli_query($conn, $sql)) {
            $message = "Payment recorded successfully.";
            $messageType = "success";
        } else {
            $message = "Error recording payment: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$payments_res = mysqli_query($conn, "SELECT * FROM payments ORDER BY payment_id DESC");

$path_to_root = "../";
$page = "payments";
$page_title = "Payments";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Record Payment</h5>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Booking (optional)</label>
                        <select name="booking_id" class="form-select">
                            <option value="">No booking</option>
                            <?php if ($bookings_res && mysqli_num_rows($bookings_res) > 0): ?>
                                <?php while ($b = mysqli_fetch_assoc($bookings_res)): ?>
                                    <option value="<?php echo $b['booking_id']; ?>">Booking #<?php echo $b['booking_id']; ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Method</label>
                        <input type="text" name="method" class="form-control" placeholder="Cash, Card, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">Save Payment</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Payments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Booking</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($payments_res && mysqli_num_rows($payments_res) > 0): ?>
                                <?php while ($p = mysqli_fetch_assoc($payments_res)): ?>
                                    <tr>
                                        <td><?php echo $p['payment_id']; ?></td>
                                        <td><?php echo ($p['booking_id'] ?? null) ? "#".$p['booking_id'] : "-"; ?></td>
                                        <td><?php echo number_format((float)($p['amount'] ?? 0), 2); ?></td>
                                        <td><?php echo htmlspecialchars($p['method'] ?? ""); ?></td>
                                        <td><?php echo htmlspecialchars($p['status'] ?? ""); ?></td>
                                        <td><?php echo $p['payment_date'] ?? ""; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No payments found.</td>
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
