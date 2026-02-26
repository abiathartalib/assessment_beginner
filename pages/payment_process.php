<?php
include "../db.php";

// Create payments table
$create_payments = "CREATE TABLE IF NOT EXISTS payments (
  payment_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_id INT(11) UNSIGNED NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(50),
  payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(50) DEFAULT 'Completed'
)";
mysqli_query($conn, $create_payments);

$message = "";
$messageType = "";

// Handle Payment
if (isset($_POST['process_payment'])) {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $method = $_POST['payment_method'];
    
    if (!empty($booking_id) && !empty($amount)) {
        mysqli_query($conn, "INSERT INTO payments (booking_id, amount, payment_method) VALUES ('$booking_id', '$amount', '$method')");
        // Optionally update booking status
        mysqli_query($conn, "UPDATE bookings SET status = 'Completed' WHERE booking_id = '$booking_id'");
        $message = "Payment processed successfully!";
        $messageType = "success";
    }
}

// Fetch Pending Bookings for Payment
$pending_bookings = mysqli_query($conn, "
    SELECT b.booking_id, c.full_name, s.service_name, s.price, b.booking_date 
    FROM bookings b 
    JOIN clients c ON b.client_id = c.client_id 
    JOIN services s ON b.service_id = s.service_id 
    WHERE b.status != 'Completed' AND b.status != 'Cancelled'
    ORDER BY b.booking_date
");

// Fetch Payment History
$history_res = mysqli_query($conn, "
    SELECT p.*, c.full_name, s.service_name 
    FROM payments p
    JOIN bookings b ON p.booking_id = b.booking_id
    JOIN clients c ON b.client_id = c.client_id
    JOIN services s ON b.service_id = s.service_id
    ORDER BY p.payment_date DESC
");

$path_to_root = "../";
$page = "payments";
$page_title = "Payments";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row g-4 fade-in">
    <!-- Process Payment Form -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-success"><i class="bi bi-credit-card-2-front me-2"></i>Process Payment</h5>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Select Booking</label>
                        <select name="booking_id" class="form-select" id="bookingSelect" onchange="updateAmount()" required>
                            <option value="" data-price="0">Choose Booking...</option>
                            <?php while($b = mysqli_fetch_assoc($pending_bookings)) { ?>
                                <option value="<?php echo $b['booking_id']; ?>" data-price="<?php echo $b['price']; ?>">
                                    <?php echo $b['full_name'] . " - " . $b['service_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Amount ($)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">$</span>
                            <input type="number" step="0.01" name="amount" id="amountInput" class="form-control border-start-0 ps-0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>

                    <button type="submit" name="process_payment" class="btn btn-success w-100 shadow-sm">
                        <i class="bi bi-check-circle me-2"></i> Confirm Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment History Table -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2"></i>Payment History</h5>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i> Export</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th class="pe-4 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($history_res)) { ?>
                                <tr>
                                    <td class="ps-4 text-muted small">#<?php echo $row['payment_id']; ?></td>
                                    <td class="fw-semibold text-dark">
                                        <?php echo $row['full_name']; ?>
                                        <small class="d-block text-muted fw-normal"><?php echo $row['service_name']; ?></small>
                                    </td>
                                    <td class="fw-bold text-success">$<?php echo number_format($row['amount'], 2); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['payment_method']; ?></span></td>
                                    <td class="text-muted small"><?php echo date('M d, Y H:i', strtotime($row['payment_date'])); ?></td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Completed</span>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if(mysqli_num_rows($history_res) == 0): ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted">No payment history found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateAmount() {
    var select = document.getElementById('bookingSelect');
    var amountInput = document.getElementById('amountInput');
    var selectedOption = select.options[select.selectedIndex];
    var price = selectedOption.getAttribute('data-price');
    
    if (price) {
        amountInput.value = price;
    } else {
        amountInput.value = '';
    }
}
</script>

<?php include "../includes/footer.php"; ?>
