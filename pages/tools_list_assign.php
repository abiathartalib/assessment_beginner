<?php
include "../db.php";

// Create tools table
$create_tools = "CREATE TABLE IF NOT EXISTS tools (
  tool_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tool_name VARCHAR(255) NOT NULL,
  status VARCHAR(50) DEFAULT 'Available',
  purchase_date DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_tools);

// Create tool_assignments table
$create_assignments = "CREATE TABLE IF NOT EXISTS tool_assignments (
  assignment_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_id INT(11) UNSIGNED NOT NULL,
  tool_id INT(11) UNSIGNED NOT NULL,
  assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  returned_at TIMESTAMP NULL
)";
mysqli_query($conn, $create_assignments);

$message = "";
$messageType = "";

// Handle Add Tool
if (isset($_POST['add_tool'])) {
    $tool_name = $_POST['tool_name'];
    $purchase_date = $_POST['purchase_date'];
    
    if (!empty($tool_name)) {
        mysqli_query($conn, "INSERT INTO tools (tool_name, purchase_date, status) VALUES ('$tool_name', '$purchase_date', 'Available')");
        $message = "Tool added successfully!";
        $messageType = "success";
    }
}

// Handle Assign Tool
if (isset($_POST['assign_tool'])) {
    $booking_id = $_POST['booking_id'];
    $tool_id = $_POST['tool_id'];
    
    if (!empty($booking_id) && !empty($tool_id)) {
        mysqli_query($conn, "INSERT INTO tool_assignments (booking_id, tool_id) VALUES ('$booking_id', '$tool_id')");
        mysqli_query($conn, "UPDATE tools SET status = 'In Use' WHERE tool_id = '$tool_id'");
        $message = "Tool assigned successfully!";
        $messageType = "success";
    }
}

// Handle Return Tool
if (isset($_GET['return_tool_id'])) {
    $tid = $_GET['return_tool_id'];
    mysqli_query($conn, "UPDATE tools SET status = 'Available' WHERE tool_id = '$tid'");
    mysqli_query($conn, "UPDATE tool_assignments SET returned_at = NOW() WHERE tool_id = '$tid' AND returned_at IS NULL");
    header("Location: tools_list_assign.php");
    exit;
}

// Fetch Tools
$tools_res = mysqli_query($conn, "SELECT * FROM tools ORDER BY tool_name");

// Fetch Assignments
$assignments_res = mysqli_query($conn, "
    SELECT ta.*, t.tool_name, b.booking_date, c.full_name 
    FROM tool_assignments ta
    JOIN tools t ON ta.tool_id = t.tool_id
    JOIN bookings b ON ta.booking_id = b.booking_id
    JOIN clients c ON b.client_id = c.client_id
    WHERE ta.returned_at IS NULL
    ORDER BY ta.assigned_at DESC
");

// Fetch available tools and active bookings for assignment form
$avail_tools = mysqli_query($conn, "SELECT * FROM tools WHERE status = 'Available'");
$active_bookings = mysqli_query($conn, "SELECT b.booking_id, c.full_name, b.booking_date FROM bookings b JOIN clients c ON b.client_id = c.client_id WHERE b.status != 'Completed' AND b.status != 'Cancelled'");

$path_to_root = "../";
$page = "tools";
$page_title = "Tools & Inventory";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row g-4 fade-in">
    <!-- Tool Inventory Section -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-tools me-2"></i>Tool Inventory</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addToolModal">
                    <i class="bi bi-plus-lg"></i> Add Tool
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tool Name</th>
                                <th>Purchase Date</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($tools_res)) { ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?php echo $row['tool_name']; ?></td>
                                    <td class="text-muted small"><?php echo $row['purchase_date']; ?></td>
                                    <td>
                                        <span class="badge <?php echo ($row['status'] == 'Available') ? 'bg-success' : 'bg-warning text-dark'; ?> bg-opacity-10 text-opacity-75 rounded-pill px-3">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <?php if($row['status'] == 'In Use'): ?>
                                            <a href="?return_tool_id=<?php echo $row['tool_id']; ?>" class="btn btn-sm btn-outline-success" title="Mark as Returned">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if(mysqli_num_rows($tools_res) == 0): ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No tools in inventory.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments Section -->
    <div class="col-lg-5">
        <!-- Assign Tool Form -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-workspace me-2"></i>Assign Tool</h5>
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
                        <label class="form-label text-muted small fw-bold text-uppercase">Select Booking</label>
                        <select name="booking_id" class="form-select" required>
                            <option value="">Choose Booking...</option>
                            <?php while($b = mysqli_fetch_assoc($active_bookings)) { ?>
                                <option value="<?php echo $b['booking_id']; ?>">
                                    <?php echo $b['full_name'] . " (" . $b['booking_date'] . ")"; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Select Tool</label>
                        <select name="tool_id" class="form-select" required>
                            <option value="">Choose Available Tool...</option>
                            <?php while($t = mysqli_fetch_assoc($avail_tools)) { ?>
                                <option value="<?php echo $t['tool_id']; ?>"><?php echo $t['tool_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" name="assign_tool" class="btn btn-primary w-100 shadow-sm">Assign Tool</button>
                </form>
            </div>
        </div>

        <!-- Active Assignments List -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-secondary text-uppercase small">Currently Assigned</h6>
            </div>
            <div class="list-group list-group-flush">
                <?php while($assign = mysqli_fetch_assoc($assignments_res)) { ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark"><?php echo $assign['tool_name']; ?></h6>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i><?php echo $assign['full_name']; ?>
                            </small>
                        </div>
                        <a href="?return_tool_id=<?php echo $assign['tool_id']; ?>" class="btn btn-sm btn-light text-success" title="Return">
                            <i class="bi bi-check-lg"></i>
                        </a>
                    </div>
                <?php } ?>
                <?php if(mysqli_num_rows($assignments_res) == 0): ?>
                    <div class="p-3 text-center text-muted small">No active assignments.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Tool Modal -->
<div class="modal fade" id="addToolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Tool</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tool Name</label>
                        <input type="text" name="tool_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_tool" class="btn btn-primary">Save Tool</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
