<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Redirect to login page
    // We need to handle path correctly depending on where this is included
    // Assuming this file is in includes/ folder, so we check relative paths
    
    // Simple logic: if we are in 'pages' folder, go up one level to login.php
    // If we are in root folder (like index.php), just go to login.php
    
    // A more robust way is to use absolute path or check current script path
    $current_script = $_SERVER['SCRIPT_NAME'];
    
    // Adjust redirect path based on depth
    if (strpos($current_script, '/pages/') !== false) {
        header("Location: ../login.php");
    } else {
        header("Location: login.php");
    }
    exit;
}
?>
