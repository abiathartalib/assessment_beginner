<?php
include "../db.php";

$message = "";
$messageType = "";

// Fetch clients and services for dropdowns
$clients_res = mysqli_query($conn, "SELECT client_id, full_name FROM clients ORDER BY full_name");
$services_res = mysqli_query($conn, "SELECT service_id, service_name, price FROM services ORDER BY service_name");

if (isset($_POST['save'])) {
    $client_id = $_POST['client_id'];
    $service_id = $_POST['service_id'];
    $booking_date = $_POST['booking_date'];
    $notes = $_POST['notes'];

    if (empty($client_id) || empty($service_id) || empty($booking_date)) {
        $message = "Client, Service, and Date are required!";
        $messageType = "danger";
    } else {
        $sql = "INSERT INTO bookings (client_id, service_id, booking_date, notes, status) 
                VALUES ('$client_id', '$service_id', '$booking_date', '$notes', 'Pending')";
        
        if(mysqli_query($conn, $sql)) {
            header("Location: bookings_list.php");
            exit;
        } else {
            $message = "Error: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$path_to_root = "../";
$page = "bookings";
$page_title = "New Booking";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row justify-content-center fade-in">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-3 mt-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-calendar-plus me-2"></i>Create New Booking</h5>
            </div>
            <div class="card-body p-4">

                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i><?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Client <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-select" required>
                            <option value="">Select Client...</option>
                            <?php while($row = mysqli_fetch_assoc($clients_res)) { ?>
                                <option value="<?php echo $row['client_id']; ?>"><?php echo $row['full_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Service <span class="text-danger">*</span></label>
                        <select name="service_id" class="form-select" required>
                            <option value="">Select Service...</option>
                            <?php while($row = mysqli_fetch_assoc($services_res)) { ?>
                                <option value="<?php echo $row['service_id']; ?>">
                                    <?php echo $row['service_name'] . " ($" . number_format($row['price'], 2) . ")"; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Date <span class="text-danger">*</span></label>
                        <input type="date" name="booking_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional details..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="bookings_list.php" class="btn btn-light text-secondary">Cancel</a>
                        <button type="submit" name="save" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
