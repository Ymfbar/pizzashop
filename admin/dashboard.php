<?php include 'admin_header.php'; ?>

<h1 class="mb-4 text-danger"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>
<p class="lead">Selamat datang, admin ganteng. Ini adalah pusat kontrol Anda.</p>
<hr>

<div class="row">
    <?php
    $count_menu = $conn->query("SELECT COUNT(*) AS total FROM menu")->fetch_assoc()['total'];
    $count_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
    $count_pending = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'")->fetch_assoc()['total'];
    ?>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h1 class="display-4"><?php echo $count_menu; ?></h1>
                        <p class="lead mb-0">Total Menu</p>
                    </div>
                    <div class="col-4 text-end"><i class="fas fa-book fa-3x"></i></div>
                </div>
            </div>
            <a href="manage_menu.php" class="card-footer text-white text-decoration-none">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h1 class="display-4"><?php echo $count_orders; ?></h1>
                        <p class="lead mb-0">Total Pesanan</p>
                    </div>
                    <div class="col-4 text-end"><i class="fas fa-receipt fa-3x"></i></div>
                </div>
            </div>
            <a href="manage_orders.php" class="card-footer text-white text-decoration-none">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h1 class="display-4"><?php echo $count_pending; ?></h1>
                        <p class="lead mb-0">Pesanan Pending</p>
                    </div>
                    <div class="col-4 text-end"><i class="fas fa-hourglass-half fa-3x"></i></div>
                </div>
            </div>
            <a href="manage_orders.php" class="card-footer text-white text-decoration-none">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>