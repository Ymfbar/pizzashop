<?php
include 'includes/config.php';
include 'includes/header.php';

// Ambil kategori dari URL, default 'pizza'
$kategori = isset($_GET['kategori']) ? strtolower($_GET['kategori']) : 'pizza';

// Daftar kategori valid
$allowed_categories = ['pizza', 'snacks', 'drinks'];

// Kalau kategori tidak valid, fallback ke pizza
if (!in_array($kategori, $allowed_categories)) {
    $kategori = 'pizza';
}

$sql = "SELECT * FROM menu WHERE kategori = ? ORDER BY harga ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kategori);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
.menu-nav {
    display: flex;
    justify-content: center;
    gap: 25px;
    margin-bottom: 35px;
    font-size: 20px;
    font-weight: bold;
}

.menu-nav a {
    text-decoration: none;
    color: #ffffffff;
    padding-bottom: 6px;
}

.menu-nav a.active {
    border-bottom: 3px solid #d32f2f;
    color: #d32f2f;
}

/* Mengatur pola background untuk menutupi seluruh halaman */
body {
    background-image: url('images/pattern2.png');
    background-size: 1700px;    
}

/* Membuat dark overlay untuk seluruh halaman */
body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: -1;
}


.card-menu {
    transition: transform 0.25s ease-in-out;
    border-radius: 14px;
}

.card-menu:hover {
    transform: translateY(-5px);
}

.btn-custom-red {
    background: #d32f2f;
    color: white;
    font-weight: bold;
    transition: 0.2s;
}

.btn-custom-red:hover {
    background: #b71c1c;
    color: white;
}
</style>

<h2 class="text-center text-warning mb-2"><i class="fas fa-utensils"></i> Our Menu</h2>
<p class="text-center text-white lead mb-4">Find your perfect bite — select a menu category below.</p>

<div class="menu-nav">
    <a href="?kategori=pizza" class="<?= $kategori == 'pizza' ? 'active' : '' ?>">Pizza's</a>
    <a href="?kategori=snacks" class="<?= $kategori == 'snacks' ? 'active' : '' ?>">Snack's</a>
    <a href="?kategori=drinks" class="<?= $kategori == 'drinks' ? 'active' : '' ?>">Drink's</a>
</div>

<div class="row">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card card-menu h-100 shadow-lg">
                    <div style="height: 350px; overflow: hidden;">
                        <img src="images/<?php echo htmlspecialchars($row['gambar']); ?>"
                             class="card-img-top"
                             alt="<?php echo htmlspecialchars($row['nama_pizza']); ?>"
                             style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                    <div class="card-body mt-2">
                        <h5 class="card-title fw-bold text-danger">
                            <?php echo htmlspecialchars($row['nama_pizza']); ?>
                        </h5>
                        <p class="card-text text-muted" style="height: 60px; overflow: hidden;">
                            <?php echo htmlspecialchars($row['deskripsi']); ?>
                        </p>
                        <p class="h4 text-black">
                            Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 d-grid">
                        <a href="order.php?item_id=<?php echo $row['id']; ?>"
                           class="btn btn-custom-red">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='text-center text-muted'>Belum ada menu pada kategori ini.</p>";
    }
    ?>
</div>

<?php
include 'includes/footer.php';
$conn->close();
?>
