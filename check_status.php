<?php
include 'includes/config.php';
include 'includes/header.php';

$orders = [];
$message = '';
$search_term = '';

// Fungsi untuk mendapatkan badge status
function getStatusBadge($status) {
    switch ($status) {
        case 'Pending': return '<span class="badge bg-warning text-dark">Pending</span>';
        case 'Diproses': return '<span class="badge bg-info">Diproses</span>';
        case 'Selesai': return '<span class="badge bg-success">Selesai</span>';
        case 'Batal': return '<span class="badge bg-danger">Batal</span>';
        default: return '<span class="badge bg-secondary">Unknown</span>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $conn->real_escape_string($_POST['telepon']);
    
    $sql = "SELECT id, nama_pelanggan, tanggal_pesan, total_harga, status, detail_pesanan, metode_pembayaran
            FROM orders 
            WHERE telepon = ? 
            ORDER BY tanggal_pesan DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $message = "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Ditemukan <strong>" . count($orders) . "</strong> pesanan.</div>";
    } else {
        $message = "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle'></i> Tidak ada pesanan untuk nomor <strong>" . htmlspecialchars($search_term) . "</strong>.</div>";
    }
    $stmt->close();
}
?>

<style>
/* BG + layout full height biar footer nempel bawah */
html, body { height: 100%; margin: 0; }
body {
    display: flex;
    flex-direction: column;
    background-image: url('images/pattern.png');
    background-size: cover;
}

/* Overlay gelap */
body::before {
    content: "";
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: -1;
}

/* Konten dorong footer ke bawah */
.page-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding-top: 30px;
}

/* Tinggi card utama biar gak kelihatan pendek */
.card {
    min-height: 360px;
}
</style>

<div class="page-content">

<h2 class="text-center text-warning mb-4"><i class="fas fa-search"></i> Cek Status Pesanan Anda</h2>
<p class="text-center text-light lead">Masukkan nomor telepon yang Anda gunakan saat memesan.</p>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-8">
        <div class="card shadow-lg p-4 mb-4">
            <form method="POST">
                <div class="mb-3">
                    <label for="telepon" class="form-label fw-bold">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="telepon" name="telepon" value="<?php echo htmlspecialchars($search_term); ?>" required placeholder="Cth: 081234567890">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-warning btn-lg"><i class="fas fa-search"></i> Cari Pesanan</button>
                </div>
                <div class="text-center mt-4">
                <img src="images/pizza.png" alt="Pizza Shop Logo" style="width:95px; display:block; margin:auto;">
                    <h3 class="fw-bold mt-2" style="color: #d32f2f; font-size: 28px;">
                    PIZZA HOUSE
                    </h3>
                </div>


            </form>
        </div>

        <?php echo $message; ?>

        <?php if (!empty($orders)): ?>
        <h4 class="mt-4 text-warning">Hasil Pesanan Anda</h4>
        <div class="list-group mb-5">
            <?php foreach($orders as $order): ?>
                <div class="list-group-item list-group-item-action mb-3 shadow-sm rounded">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1 text-danger">Order ID #<?php echo $order['id']; ?></h5>
                        <small class="text-muted"><?php echo date('d M Y H:i', strtotime($order['tanggal_pesan'])); ?></small>
                    </div>
                    <p class="mb-1"><strong>Status Saat Ini:</strong> <?php echo getStatusBadge($order['status']); ?></p>
                    <p class="mb-1"><strong>Total Harga:</strong> Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></p>
                    <small class="text-muted">
                        <strong>Detail Pesanan:</strong> <?php echo htmlspecialchars(substr(str_replace(["\\r\\n", "\\n", "\\r"], " ", $order['detail_pesanan']), 0, 70)) . '...'; ?>
                        <a data-bs-toggle="collapse" href="#detail-<?php echo $order['id']; ?>">[Lihat Semua Detail]</a>
                    </small>
                    <div class="collapse mt-2" id="detail-<?php echo $order['id']; ?>">
                        <div class="card card-body" style="
    background: white;
    font-family: 'Courier New', monospace;
    font-size: 15px;
    border: 1px solid #ddd;
    padding: 20px;
    width: 100%;
">

<?php
// Bersihkan baris dari database
$detail = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $order['detail_pesanan']);
$items = array_filter(array_map('trim', explode("\n", $detail)));

// Parsing item menjadi: qty | nama | harga total
$parsed = [];
foreach ($items as $line) {
    // format contoh: "3x Cheese Bomb (Rp 100 x 3)"
    if (preg_match('/(\d+)x\s+(.*?)\s+\(Rp\s*([\d\.]+) x (\d+)\)/i', $line, $m)) {
        // Ambil Qty (m[1]) dan Harga Satuan (m[3])
        $qty = (int) $m[1];
        // Hapus tanda titik/koma dan konversi ke integer
        $unit_price = (int) str_replace('.', '', $m[3]); 
        
        // Hitung Harga Total Per Item: Qty * Harga Satuan
        $total_price_item = $qty * $unit_price; 
        
        $parsed[] = [
            'qty' => $qty,
            'name' => $m[2],
            // Simpan harga total item (sudah diformat)
            'price' => number_format($total_price_item, 0, ',', '.')
        ];
    } else {
        $parsed[] = ['qty' => '', 'name' => $line, 'price' => ''];
    }
}
?>

<div style="white-space: pre; line-height: 1.4;">

<?php foreach ($parsed as $p): ?>
<?php
$qty = str_pad($p['qty'], 2, " ", STR_PAD_LEFT);
$name = str_pad($p['name'], 25);
$price = str_pad($p['price'], 10, " ", STR_PAD_LEFT);
?>
<?= $qty ?> <?= $name ?> <?= $price . "\n" ?>
<?php endforeach; ?>

--------------------------------
Subtotal :      Rp <?= number_format($order['total_harga'], 0, ',', '.') . "\n" ?>
Total    :      Rp <?= number_format($order['total_harga'], 0, ',', '.') . "\n" ?>
Payment  :      Rp <?= number_format($order['total_harga'], 0, ',', '.') . "\n" ?>
Via <?= strtoupper($order['metode_pembayaran']); ?>

--------------------------------
#<?= $order['id']; ?>     CLOSED <?= date('d M y H:i', strtotime($order['tanggal_pesan'])) ?>

</div>
</div>

                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

</div> <!-- END page-content -->


<?php
include 'includes/footer.php';
$conn->close();
?>
