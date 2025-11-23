<?php
include 'includes/config.php';
include 'includes/header.php';

$message = '';
$menu_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;
$menu_select = '';
$initial_price = 0;

// Ambil data menu untuk dropdown
$menu_result = $conn->query("SELECT id, nama_pizza, harga FROM menu ORDER BY nama_pizza ASC");
$menu_options = [];
while ($row = $menu_result->fetch_assoc()) {
    $menu_options[] = $row;
    if ($row['id'] == $menu_id) {
        $menu_select = "1x " . $row['nama_pizza'] . " (Rp " . number_format($row['harga'], 0, ',', '.') . ")";
        $initial_price = $row['harga'];
    }
}

// Proses Form Pemesanan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pelanggan = $conn->real_escape_string($_POST['nama_pelanggan']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $telepon = $conn->real_escape_string($_POST['telepon']);
    $detail_pesanan = $conn->real_escape_string($_POST['detail_pesanan']);
    $raw_total_harga = str_replace(',', '.', $_POST['total_harga']);
    $total_harga = (float)$raw_total_harga;

    // Upload bukti pembayaran
    $bukti = null;

    if (!empty($_FILES['bukti_pembayaran']['name'])) {
        $upload_dir = "uploads/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES['bukti_pembayaran']['name']);
        $target_path = $upload_dir . $file_name;

        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_ext)) {
            if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $target_path)) {
                $bukti = $file_name;
            }
        }
    }

    $sql = "INSERT INTO orders (nama_pelanggan, alamat, telepon, detail_pesanan, total_harga, bukti_pembayaran) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssds", $nama_pelanggan, $alamat, $telepon, $detail_pesanan, $total_harga, $bukti);

    if ($stmt->execute()) {
        header("Location: order.php?success=1");
        exit;
    } else {
        $message = "<div class='alert alert-danger'><i class='fas fa-times-circle'></i> Terjadi kesalahan: " . $conn->error . "</div>";
    }

    $stmt->close();
}
?>

<h2 class="text-center text-warning mb-4"><i class="fas fa-shopping-cart"></i> Formulir Pemesanan</h2>
<p class="text-center text-white lead">Lengkapi detail Anda dan pesanan Anda siap diantar!</p>

<?php 
if (isset($_GET['success'])) {
    echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Pesanan Anda berhasil dicatat! Kami akan segera memprosesnya.</div>";
} 
?>

<style>
    body {
        background-image: url('images/pattern.png');
        background-size: 1700px;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: -1;
    }

    .bg-overlay {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 40px 0;
        min-height: calc(100vh - 150px);
    }

    .card {
        min-height: 330px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7 col-sm-9">
        <div class="card shadow-lg p-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama_pelanggan" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                </div>
                <div class="mb-3">
                    <label for="telepon" class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="telepon" name="telepon" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Pengiriman Lengkap</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                
                <h5 class="mt-4 mb-3 text-danger">Detail Pesanan</h5>
                
                <div class="mb-3">
                    <label for="pilihan_menu" class="form-label">Pilih Pizza</label>
                    <select class="form-select" id="pilihan_menu">
                        <option value="0">-- Pilih Pizza --</option>
                        <?php foreach($menu_options as $opt): ?>
                            <option value="<?php echo $opt['harga']; ?>" 
                                    data-name="<?php echo $opt['nama_pizza']; ?>"
                                    <?php echo ($opt['id'] == $menu_id) ? 'selected' : ''; ?>>
                                <?php echo $opt['nama_pizza']; ?> (Rp <?php echo number_format($opt['harga'], 0, ',', '.'); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Daftar Pesanan</label>
                    <ul id="daftar_pesanan" class="list-group mb-2"></ul>
                </div>
                
                <textarea class="form-control" id="detail_pesanan" name="detail_pesanan" rows="5" readonly required><?php echo $menu_select; ?></textarea>

                <div class="mb-3 mt-3">
                    <label for="total_harga" class="form-label">Total Harga (Rp)</label>
                    <input type="number" class="form-control fw-bold" id="total_harga" name="total_harga" value="<?php echo $initial_price; ?>" required step="any">
                </div>

                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                    <!-- <asmall class="text-shadow d-block">BNI 00885967</small>
                    <asmall class="text-shadow d-block">BRI 00885967</small>
                    <asmall class="text-shadow d-block">DANA 00885967</small> -->
                    <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-custom-red btn-lg"><i class="fas fa-paper-plane"></i> Kirim Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectMenu = document.getElementById('pilihan_menu');
    const detailField = document.getElementById('detail_pesanan');
    const totalHargaField = document.getElementById('total_harga');
    const daftarPesanan = document.getElementById('daftar_pesanan');

    let pesanan = [];
    let total = parseFloat(totalHargaField.value) || 0;

    // Jika datang dari GET item_id (pesanan awal)
    if (detailField.value.trim() !== "" && total > 0) {
        const firstItem = detailField.value.replace("1x ", "").split(" (Rp")[0];
        pesanan.push({ nama: firstItem, harga: total });
    }

    function renderPesanan() {
        daftarPesanan.innerHTML = "";

        pesanan.forEach((item, index) => {
            const li = document.createElement("li");
            li.className = "list-group-item d-flex justify-content-between align-items-center";
            li.innerHTML = `
                ${item.nama} — Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}
                <button type="button" class="btn btn-sm btn-danger" onclick="hapusPesanan(${index})">✖</button>
            `;
            daftarPesanan.appendChild(li);
        });

        detailField.value = pesanan
            .map(item => `1x ${item.nama} (Rp ${new Intl.NumberFormat('id-ID').format(item.harga)})`)
            .join('\\n');

        total = pesanan.reduce((sum, item) => sum + item.harga, 0);
        totalHargaField.value = total;
    }

    window.hapusPesanan = function(index) {
        pesanan.splice(index, 1);
        renderPesanan();
    }

    selectMenu.addEventListener('change', function() {
        const selectedOption = selectMenu.options[selectMenu.selectedIndex];
        const harga = parseFloat(selectedOption.value) || 0;
        const nama = selectedOption.getAttribute('data-name');

        if (harga > 0) {
            pesanan.push({ nama, harga });
            renderPesanan();
        }
    });

    renderPesanan();
});
</script>

<?php
include 'includes/footer.php';
$conn->close();
?>
