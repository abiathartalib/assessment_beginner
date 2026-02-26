<?php
include "../db.php";

// Create bookings table if not exists
$create = "CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  client_id INT(11) UNSIGNED NOT NULL,
  service_id INT(11) UNSIGNED NOT NULL,
  booking_date DATE NOT NULL,
  notes TEXT,
  status VARCHAR(50) DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create);

// Fetch bookings with client and service names
$query = "SELECT b.*, c.full_name, s.service_name 
          FROM bookings b
          LEFT JOIN clients c ON b.client_id = c.client_id
          LEFT JOIN services s ON b.service_id = s.service_id
          ORDER BY b.booking_date DESC";
$result = mysqli_query($conn, $query);

$path_to_root = "../";
$page = "bookings";
$page_title = "Bookings";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
  <div>
    <h2 class="fw-bold mb-1">Bookings</h2>
    <p class="text-muted mb-0">Manage service appointments</p>
  </div>
  <a href="bookings_create.php" class="btn btn-primary shadow-sm">
    <i class="bi bi-plus-lg me-1"></i> New Booking
  </a>
</div>

<div class="card shadow-sm border-0 fade-in" style="animation-delay: 0.1s;">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead>
          <tr>
            <th class="py-3 ps-4">ID</th>
            <th class="py-3">Client</th>
            <th class="py-3">Service</th>
            <th class="py-3">Date</th>
            <th class="py-3">Status</th>
            <th class="py-3 pe-4 text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td class="ps-4 text-muted">#<?php echo $row['booking_id']; ?></td>
              <td class="fw-semibold text-dark">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; font-size: 0.8rem;">
                        <?php echo strtoupper(substr($row['full_name'] ?? '?', 0, 1)); ?>
                    </div>
                    <?php echo $row['full_name'] ?? 'Unknown Client'; ?>
                </div>
              </td>
              <td class="text-secondary">
                <span class="badge bg-light text-dark border">
                    <?php echo $row['service_name'] ?? 'Unknown Service'; ?>
                </span>
              </td>
              <td class="text-secondary">
                <i class="bi bi-calendar3 me-2 text-muted"></i>
                <?php echo date('M d, Y', strtotime($row['booking_date'])); ?>
              </td>
              <td>
                <?php 
                    $statusClass = 'bg-secondary';
                    if($row['status'] == 'Pending') $statusClass = 'bg-warning text-dark';
                    elseif($row['status'] == 'Confirmed') $statusClass = 'bg-info text-dark';
                    elseif($row['status'] == 'Completed') $statusClass = 'bg-success';
                    elseif($row['status'] == 'Cancelled') $statusClass = 'bg-danger';
                ?>
                <span class="badge <?php echo $statusClass; ?> bg-opacity-10 text-opacity-75 px-3 py-2 rounded-pill">
                    <?php echo $row['status']; ?>
                </span>
              </td>
              <td class="pe-4 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-light text-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="payment_process.php?booking_id=<?php echo $row['booking_id']; ?>"><i class="bi bi-credit-card me-2"></i>Process Payment</a></li>
                        <li><a class="dropdown-item" href="tools_list_assign.php?booking_id=<?php echo $row['booking_id']; ?>"><i class="bi bi-tools me-2"></i>Assign Tools</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Cancel</a></li>
                    </ul>
                </div>
              </td>
            </tr>
          <?php } ?>
          <?php if(mysqli_num_rows($result) == 0): ?>
            <tr>
              <td colspan="6" class="text-center py-5 text-muted">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-calendar-x fs-1 mb-3 text-secondary opacity-50"></i>
                    <p class="mb-0">No bookings found.</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
