<?php include 'includes/header_admin.php'; ?>
<div class="hero-section text-center rounded shadow-lg d-flex flex-column justify-content-center"
     style="
        background-image: url('images/pattern.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 450px;
        padding: 40px;
        position: relative;
        color: white;
        overflow: hidden;
     ">

    <!-- Overlay gelap biar teks kebaca -->
    <div style="
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        border-radius: inherit;
    "></div>

    <div style="position: relative; z-index: 2;">
        <h1 class="display-4 fw-bold mb-3">Pizza Shop!</h1>
        <p class="lead mb-4">Rasakan kelezatan pizza otentik dengan harga terbaik di kota.</p>
        <a href="menu.php" class="btn btn-warning btn-lg me-4">Lihat Menu Kami</a>
        <a href="order.php" class="btn btn-outline-light btn-lg">Pesan Sekarang</a>
    </div>
</div>


<div class="row text-center">
    <div class="col-md-4">
        <i class="fas fa-fire fa-3x text-danger mb-3"></i>
        <h4>Baru Dipanggang</h4>
        <p>Setiap pizza kami dibuat fresh dan dipanggang sempurna.</p>
    </div>
    <div class="col-md-4">
        <i class="fas fa-truck fa-3x text-warning mb-3"></i>
        <h4>Pengiriman Cepat</h4>
        <p>Kami menjamin pizza Anda tiba dalam kondisi hangat dan cepat.</p>
    </div>
    <div class="col-md-4">
        <i class="fas fa-hand-holding-heart fa-3x text-success mb-3"></i>
        <h4>Bahan Berkualitas</h4>
        <p>Hanya menggunakan bahan-bahan terbaik untuk rasa maksimal.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>