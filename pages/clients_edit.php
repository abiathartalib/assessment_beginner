<?php
include "../includes/auth.php";
include "../db.php";

$id = $_GET['id'];
$get = mysqli_query($conn, "SELECT * FROM clients WHERE client_id = $id");
$client = mysqli_fetch_assoc($get);

$message = "";
$messageType = "";

if (!$client) {
    $message = "Client not found.";
    $messageType = "danger";
} else {
    if (isset($_POST['update'])) {
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        if ($full_name == "") {
            $message = "Full name is required.";
            $messageType = "danger";
        } else {
            $sql = "UPDATE clients SET full_name='$full_name', email='$email', phone='$phone', address='$address' WHERE client_id = $id";
            if (mysqli_query($conn, $sql)) {
                $message = "Client updated successfully.";
                $messageType = "success";
                $get = mysqli_query($conn, "SELECT * FROM clients WHERE client_id = $id");
                $client = mysqli_fetch_assoc($get);
            } else {
                $message = "Error updating client: " . mysqli_error($conn);
                $messageType = "danger";
            }
        }
    }
}

$path_to_root = "../";
$page = "clients";
$page_title = "Edit Client";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Client</h5>
                <a href="clients_list.php" class="btn btn-sm btn-outline-secondary">Back to Clients</a>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>

                <?php if ($client): ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($client['full_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($client['email']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($client['phone']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($client['address']); ?></textarea>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update Client</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
