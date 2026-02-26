<!-- Sidebar -->
<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="bi bi-grid-1x2-fill me-3"></i>
        <span>CMS Admin</span>
    </div>
    <div class="list-group my-3">
        <a href="<?php echo $path_to_root; ?>index.php" class="list-group-item list-group-item-action <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
        <a href="<?php echo $path_to_root; ?>pages/clients_list.php" class="list-group-item list-group-item-action <?php echo ($page == 'clients') ? 'active' : ''; ?>">
            <i class="bi bi-people"></i>Clients
        </a>
        <a href="<?php echo $path_to_root; ?>pages/services_list.php" class="list-group-item list-group-item-action <?php echo ($page == 'services') ? 'active' : ''; ?>">
            <i class="bi bi-briefcase"></i>Services
        </a>
        <a href="<?php echo $path_to_root; ?>pages/bookings_list.php" class="list-group-item list-group-item-action <?php echo ($page == 'bookings') ? 'active' : ''; ?>">
            <i class="bi bi-calendar-check"></i>Bookings
        </a>
        <a href="<?php echo $path_to_root; ?>pages/tools_list_assign.php" class="list-group-item list-group-item-action <?php echo ($page == 'tools') ? 'active' : ''; ?>">
            <i class="bi bi-tools"></i>Tools & Inventory
        </a>
        <a href="<?php echo $path_to_root; ?>pages/payment_process.php" class="list-group-item list-group-item-action <?php echo ($page == 'payments') ? 'active' : ''; ?>">
            <i class="bi bi-credit-card"></i>Payments
        </a>
    </div>
</div>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="bi bi-list fs-4 me-3 text-primary cursor-pointer" id="menu-toggle"></i>
            <h2 class="fs-4 m-0 fw-bold text-dark"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h2>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-secondary fw-bold" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>Admin User
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
