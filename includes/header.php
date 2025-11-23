<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Shop | Pizza Lezat Harga Bersahabat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('images/hero.jpg') center/cover no-repeat;
            color: white;
            padding: 80px 0;
            margin-bottom: 30px;
        }
        .card-menu {
            transition: transform 0.2s;
            border: none;
        }
        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .bg-custom-red {
            background-color: #dc3545;
        }
        
        /* 🚨 CSS KUSTOM TAMBAHAN UNTUK PENANDA GARIS BAWAH */
        .navbar-nav .nav-link {
            position: relative; /* Diperlukan untuk pseudo-element ::after */
            padding-bottom: 8px; /* Ruang untuk garis bawah */
            transition: color 0.3s ease; /* Transisi untuk warna teks */
        }

        .navbar-nav .nav-link.active {
            color: white !important; /* Warna teks aktif */
            font-weight: bold; /* Teks tebal untuk aktif */
        }

        .navbar-nav .nav-link.active::after {
            content: ''; /* Wajib untuk pseudo-element */
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0; /* Posisikan di bagian bawah link */
            height: 3px; /* Ketebalan garis */
            background-color: white; /* Warna garis */
            border-radius: 2px; /* Sedikit lengkungan pada garis */
        }

        /* Hover effect (opsional) */
        .navbar-nav .nav-link:not(.active):hover {
            color: rgba(255, 255, 255, 0.75) !important; /* Sedikit lebih terang saat hover */
        }
        
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-custom-red sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php">🍕 PIZZA HOUSE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php' || $current_page == '') ? 'active' : ''; ?>" 
                       href="index.php" 
                       <?php echo ($current_page == 'index.php' || $current_page == '') ? 'aria-current="page"' : ''; ?>>
                       Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'menu.php') ? 'active' : ''; ?>" 
                       href="menu.php" 
                       <?php echo ($current_page == 'menu.php') ? 'aria-current="page"' : ''; ?>>
                       Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'order.php') ? 'active' : ''; ?>" 
                       href="order.php" 
                       <?php echo ($current_page == 'order.php') ? 'aria-current="page"' : ''; ?>>
                       Order
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'check_status.php') ? 'active' : ''; ?>" 
                       href="check_status.php" 
                       <?php echo ($current_page == 'check_status.php') ? 'aria-current="page"' : ''; ?>>
                       Status Order
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" 
                       href="about.php" 
                       <?php echo ($current_page == 'about.php') ? 'aria-current="page"' : ''; ?>>
                       About & Contact
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">