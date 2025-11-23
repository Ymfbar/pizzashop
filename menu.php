<?php
include 'includes/config.php';
include 'includes/header.php';

$sql = "SELECT * FROM menu ORDER BY harga ASC";
$result = $conn->query($sql);
?>

<h2 class="text-center text-danger mb-4"><i class="fas fa-book-open"></i> Daftar Menu Kami</h2>
<p class="text-center text-muted lead">Pilih pizza favorit Anda dari beragam pilihan yang kami sediakan.</p>



<div class="row">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card card-menu h-100 shadow-lg">
                    <div style="height: 400px; overflow: hidden;">
                        <img src="images/<?php echo htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['nama_pizza']); ?>" style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                    <div class="card-body mt-2">
                        <h5 class="card-title fw-bold text-danger"><?php echo htmlspecialchars($row['nama_pizza']); ?></h5>
                        <p class="card-text text-muted" style="height: 60px; overflow: hidden;"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        <p class="h4 text-black">Rp <?php echo number_format($row['harga'], 3, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 d-grid">
                        <a href="order.php?item_id=<?php echo $row['id']; ?>" class="btn btn-custom-red">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='text-center'>Belum ada menu yang tersedia saat ini.</p>";
    }
    ?>
</div>

<?php
include 'includes/footer.php';
$conn->close();
?>