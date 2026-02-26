<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $path_to_root; ?>assets/css/style.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2942/2942544.png" type="image/png">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Page Transition Animation */
        body {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        body.loaded {
            opacity: 1;
        }
    </style>
</head>
<body onload="document.body.classList.add('loaded')">
    <div class="d-flex" id="wrapper">
