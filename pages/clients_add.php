<?php
include "../includes/auth.php";
include "../db.php";

$message = "";
$messageType = "";

if (isset($_POST['save'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if ($full_name == "") {
        $message = "Full name is required.";
        $messageType = "danger";
    } else {
        $sql = "INSERT INTO clients (full_name, email, phone, address) VALUES ('$full_name', '$email', '$phone', '$address')";
        if (mysqli_query($conn, $sql)) {
            $message = "Client added successfully.";
            $messageType = "success";
        } else {
            $message = "Error adding client: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$path_to_root = "../";
$page = "clients";
$page_title = "Add Client";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Client</h5>
                <a href="clients_list.php" class="btn btn-sm btn-outline-secondary">Back to Clients</a>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">Save Client</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
