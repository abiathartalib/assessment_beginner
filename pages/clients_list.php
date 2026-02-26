<?php
include "../db.php";
$result = mysqli_query($conn, "SELECT * FROM clients ORDER BY client_id DESC");

$path_to_root = "../";
$page = "clients";
$page_title = "Clients";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
  <div>
    <h2 class="fw-bold mb-1">Clients</h2>
    <p class="text-muted mb-0">Manage your client database</p>
  </div>
  <a href="clients_add.php" class="btn btn-primary shadow-sm">
    <i class="bi bi-plus-lg me-1"></i> Add Client
  </a>
</div>

<div class="card shadow-sm border-0 fade-in" style="animation-delay: 0.1s;">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="bg-light">
          <tr>
            <th class="py-3 ps-4 text-secondary text-uppercase text-xs font-weight-bolder opacity-7">ID</th>
            <th class="py-3 text-secondary text-uppercase text-xs font-weight-bolder opacity-7">Name</th>
            <th class="py-3 text-secondary text-uppercase text-xs font-weight-bolder opacity-7">Email</th>
            <th class="py-3 text-secondary text-uppercase text-xs font-weight-bolder opacity-7">Phone</th>
            <th class="py-3 pe-4 text-end text-secondary text-uppercase text-xs font-weight-bolder opacity-7">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td class="ps-4 text-muted">#<?php echo $row['client_id']; ?></td>
              <td class="fw-semibold text-dark">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; font-size: 0.9rem;">
                        <?php echo strtoupper(substr($row['full_name'], 0, 1)); ?>
                    </div>
                    <?php echo $row['full_name']; ?>
                </div>
              </td>
              <td><a href="mailto:<?php echo $row['email']; ?>" class="text-decoration-none text-secondary"><?php echo $row['email']; ?></a></td>
              <td class="text-secondary"><?php echo $row['phone']; ?></td>
              <td class="pe-4 text-end">
                <a href="clients_edit.php?id=<?php echo $row['client_id']; ?>" class="btn btn-sm btn-light text-primary">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
              </td>
            </tr>
          <?php } ?>
          <?php if(mysqli_num_rows($result) == 0): ?>
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-people fs-1 mb-3 text-secondary opacity-50"></i>
                    <p class="mb-0">No clients found.</p>
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
