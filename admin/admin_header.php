<?php
session_start();
include '../includes/config.php';

// Proteksi Halaman Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .bg-custom-red { background-color: #dc3545; }
        .sidebar { min-height: 100vh; }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar" style="width: 250px;">
        <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="fas fa-pizza-slice me-2 fs-4"></i>
            <span class="fs-5 fw-bold">ADMIN PANEL</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link text-white <?php echo ($current_page == 'dashboard.php' ? 'active bg-custom-red' : ''); ?>">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="manage_menu.php" class="nav-link text-white <?php echo ($current_page == 'manage_menu.php' || $current_page == 'tambah_menu.php' || $current_page == 'edit_menu.php' ? 'active bg-custom-red' : ''); ?>">
                    <i class="fas fa-book me-2"></i> Kelola Menu
                </a>
            </li>
            <li>
                <a href="manage_orders.php" class="nav-link text-white <?php echo ($current_page == 'manage_orders.php' ? 'active bg-custom-red' : ''); ?>">
                    <i class="fas fa-receipt me-2"></i> Kelola Order
                </a>
            </li>
        </ul>
        <hr>
        <div>
            <a href="logout.php" class="btn btn-outline-light btn-sm d-block"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="flex-grow-1 p-4">