<?php
include 'includes/config.php';
include 'includes/header.php';

$message = '';
$menu_result = $conn->query("SELECT id, nama_pizza, harga FROM menu ORDER BY nama_pizza ASC");
$menu_options = [];
while ($row = $menu_result->fetch_assoc()) {
    $menu_options[] = $row;
}

// Proses Form Pemesanan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pelanggan = $conn->real_escape_string($_POST['nama_pelanggan']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $telepon = $conn->real_escape_string($_POST['telepon']);
    $detail_pesanan = $conn->real_escape_string($_POST['detail_pesanan']);
    $total_harga = (float)$_POST['total_harga'];

    // Upload bukti pembayaran
    $bukti = null;
    if (!empty($_FILES['bukti_pembayaran']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

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
        header("Location: order.php?success=1&order_id=" . $conn->insert_id);
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
    echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Pesanan Anda berhasil dicatat! ";
    echo "Nomor ID Pesanan Anda adalah: <b>#" . htmlspecialchars($_GET['order_id']) . "</b>. ";
    echo "<a href='check_status.php' class='alert-link'>Cek Status Pesanan</a>.</div>";
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
</style>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7 col-sm-9">
        <div class="card shadow-lg p-4">
            <?= $message; ?>
            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_pelanggan" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" name="telepon" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Pengiriman Lengkap</label>
                    <textarea class="form-control" name="alamat" rows="3" required></textarea>
                </div>

                <h5 class="mt-4 mb-3 text-danger">Detail Pesanan</h5>

                <div class="row mb-3">
                    <div class="col-7">
                        <label class="form-label">Pilih Pizza</label>
                        <select class="form-select" id="pilihan_menu">
                            <option value="0" data-name="">-- Pilih Pizza --</option>
                            <?php foreach ($menu_options as $opt): ?>
                                <option value="<?= $opt['harga']; ?>" data-name="<?= $opt['nama_pizza']; ?>">
                                    <?= $opt['nama_pizza']; ?> (Rp <?= number_format($opt['harga'], 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-5">
                        <label class="form-label">Jumlah </label>
                        <input type="number" min="1" value="1" class="form-control" id="qty_menu">
                    </div>
                </div>

                <button type="button" class="btn btn-warning w-100 mb-3" id="tambah_menu">➕ Tambah ke Pesanan</button>

                <ul id="daftar_pesanan" class="list-group mb-1"></ul>

                <textarea class="form-control mt-2" id="detail_pesanan" name="detail_pesanan" rows="5" readonly required></textarea>

                <label class="form-label mt-3">Total Harga (Rp)</label>
                <input type="number" class="form-control fw-bold" id="total_harga" name="total_harga" readonly required>

                <div class="mb-3 mt-4">
                    <label class="form-label fw-bold">Metode Pembayaran</label>
                    <div class="p-3 rounded shadow-sm bg-light mb-3">
                        <p class="mb-1"><i class="fas fa-university text-primary me-2"></i><strong>BNI:</strong> 00885967</p>
                        <p class="mb-1"><i class="fas fa-university text-success me-2"></i><strong>BRI:</strong> 982201234567890</p>
                        <p class="mb-0"><i class="fas fa-wallet text-info me-2"></i><strong>DANA:</strong> 0896-1234-5678</p>
                    </div>
                    <label class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" class="form-control" name="bukti_pembayaran" required accept="image/*">
                    <small class="text-muted">Format diterima: JPG, PNG, WEBP (maks 2MB)</small>
                </div>

                <button type="submit" class="btn btn-danger btn-lg w-100"><i class="fas fa-paper-plane"></i> Kirim Pesanan</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectMenu = document.getElementById('pilihan_menu');
    const qtyField = document.getElementById('qty_menu');
    const tambahMenu = document.getElementById('tambah_menu');
    const detailField = document.getElementById('detail_pesanan');
    const daftarPesanan = document.getElementById('daftar_pesanan');
    const totalHargaField = document.getElementById('total_harga');

    let pesanan = [];
    let total = 0;

    function renderPesanan() {
        daftarPesanan.innerHTML = "";
        total = 0;

        pesanan.forEach((item, index) => {
            const subtotal = item.harga * item.qty;
            total += subtotal;

            const li = document.createElement("li");
            li.className = "list-group-item d-flex justify-content-between";
            li.innerHTML = `
                ${item.qty}× ${item.nama} — Rp ${new Intl.NumberFormat("id-ID").format(subtotal)}
                <button class="btn btn-sm btn-danger" onclick="hapusPesanan(${index})">✖</button>
            `;
            daftarPesanan.appendChild(li);
        });

        detailField.value = pesanan
            .map(item => `${item.qty}x ${item.nama} (Rp ${item.harga} × ${item.qty})`)
            .join("\n");

        totalHargaField.value = total;
    }

    window.hapusPesanan = index => {
        pesanan.splice(index, 1);
        renderPesanan();
    };

    tambahMenu.addEventListener('click', function() {
        const selected = selectMenu.options[selectMenu.selectedIndex];
        const harga = parseFloat(selected.value);
        const nama = selected.getAttribute("data-name");
        const qty = parseInt(qtyField.value);

        if (!harga || qty < 1) return;

        pesanan.push({ nama, harga, qty });
        renderPesanan();
    });

    renderPesanan();
});
</script>

<?php include 'includes/footer.php'; $conn->close(); ?>
