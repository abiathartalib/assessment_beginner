<?php
include "../includes/auth.php";
include "../db.php";

$message = "";
$messageType = "";

// Fetch clients and services for dropdowns
$clients_res = mysqli_query($conn, "SELECT client_id, full_name FROM clients ORDER BY full_name");
$services_res = mysqli_query($conn, "SELECT service_id, service_name, price FROM services ORDER BY service_name");

$clients = [];
while ($row = mysqli_fetch_assoc($clients_res)) {
    $clients[] = $row;
}

$services = [];
while ($row = mysqli_fetch_assoc($services_res)) {
    $services[] = $row;
}

if (isset($_POST['save'])) {
    $client_id = isset($_POST['client_id']) ? (int) $_POST['client_id'] : 0;
    $service_id = isset($_POST['service_id']) ? (int) $_POST['service_id'] : 0;
    $booking_date = $_POST['booking_date'] !== "" ? $_POST['booking_date'] : date('Y-m-d');
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $total_amount = $_POST['total_amount'] !== "" ? (float) $_POST['total_amount'] : 0;
    $status = $_POST['status'] !== "" ? mysqli_real_escape_string($conn, $_POST['status']) : "Pending";

    if ($client_id <= 0 || $service_id <= 0) {
        $message = "Please select client and service.";
        $messageType = "danger";
    } else {
        $sql = "INSERT INTO bookings (client_id, service_id, booking_date, notes, total_amount, status) VALUES ($client_id, $service_id, '$booking_date', '$notes', $total_amount, '$status')";
        if (mysqli_query($conn, $sql)) {
            $message = "Booking created successfully.";
            $messageType = "success";
        } else {
            $message = "Error creating booking: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$path_to_root = "../";
$page = "bookings";
$page_title = "Create Booking";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Booking</h5>
                <a href="bookings_list.php" class="btn btn-sm btn-outline-secondary">Back to Bookings</a>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <select name="client_id" class="form-select" required>
                            <option value="">Select client</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['client_id']; ?>"><?php echo htmlspecialchars($client['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-select" required>
                            <option value="">Select service</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo $service['service_id']; ?>">
                                    <?php echo htmlspecialchars($service['service_name']); ?> (<?php echo number_format($service['price'], 2); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Booking Date</label>
                        <input type="date" name="booking_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Amount</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">Save Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
