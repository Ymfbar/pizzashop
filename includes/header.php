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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="order.php">Order</a></li>
                 <li class="nav-item"><a class="nav-link" href="check_status.php">Status Order</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About & Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">