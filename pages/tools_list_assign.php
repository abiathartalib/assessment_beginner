<?php
include "../includes/auth.php";
include "../db.php";

$message = "";
$messageType = "";

if (isset($_POST['save'])) {
    $tool_name = mysqli_real_escape_string($conn, $_POST['tool_name']);
    $quantity = $_POST['quantity'] !== "" ? (int) $_POST['quantity'] : 0;
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = $_POST['status'] !== "" ? mysqli_real_escape_string($conn, $_POST['status']) : "Available";

    if ($tool_name == "") {
        $message = "Tool name is required.";
        $messageType = "danger";
    } else {
        $sql = "INSERT INTO tools (tool_name, quantity, location, status) VALUES ('$tool_name', $quantity, '$location', '$status')";
        if (mysqli_query($conn, $sql)) {
            $message = "Tool saved successfully.";
            $messageType = "success";
        } else {
            $message = "Error saving tool: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    if ($delete_id > 0) {
        if (mysqli_query($conn, "DELETE FROM tools WHERE tool_id = $delete_id")) {
            $message = "Tool deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Error deleting tool: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM tools ORDER BY tool_id DESC");

$path_to_root = "../";
$page = "tools";
$page_title = "Tools & Inventory";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="row mb-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add / Update Tool</h5>
            </div>
            <div class="card-body">
                <?php if ($message != ""): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Tool Name</label>
                        <input type="text" name="tool_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Available">Available</option>
                            <option value="In Use">In Use</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">Save Tool</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tool Inventory</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['tool_id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['tool_name']); ?></td>
                                        <td><?php echo $row['quantity'] ?? 0; ?></td>
                                        <td><?php echo htmlspecialchars($row['location'] ?? ""); ?></td>
                                        <td><?php echo htmlspecialchars($row['status'] ?? ""); ?></td>
                                        <td><?php echo $row['created_at'] ?? ""; ?></td>
                                        <td class="text-end">
                                            <a href="tools_list_assign.php?delete_id=<?php echo $row['tool_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this tool?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No tools found.</td>
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
