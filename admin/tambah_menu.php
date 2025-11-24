<?php
include 'admin_header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $kategori = $conn->real_escape_string($_POST['kategori']);

    // ✅ FIX HARGA — buang semua non angka agar 100.000 tetap jadi 100000
    $harga_input = $_POST['harga'];
    $harga = (float) preg_replace('/\D/', '', $harga_input);

    // Proses Upload Gambar
    $gambar_nama = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../images/";
        $file_ext = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $gambar_nama = uniqid() . '.' . $file_ext; 
        $target_file = $target_dir . $gambar_nama;
        
        if (getimagesize($_FILES["gambar"]["tmp_name"]) && in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $message = "<div class='alert alert-danger'>Gagal mengupload file gambar.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>File bukan gambar atau format tidak diizinkan.</div>";
        }
    } else {
         $message = "<div class='alert alert-danger'>Gambar wajib diupload.</div>";
    }

    if ($message == '') {
        // ✅ INSERT MENU DENGAN KATEGORI & HARGA UTUH
        $sql = "INSERT INTO menu (nama_pizza, deskripsi, harga, gambar, kategori) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $nama, $deskripsi, $harga, $gambar_nama, $kategori);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<div class='alert alert-success'>Menu <strong>" . htmlspecialchars($nama) . "</strong> berhasil ditambahkan.</div>";
            header("location: manage_menu.php");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<h2 class="mb-4 text-custom-red"><i class="fas fa-plus-circle"></i> Tambah Menu Baru</h2>
<?php echo $message; ?>

<div class="card shadow-lg p-4">
    <form method="POST" enctype="multipart/form-data">
        
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Menu</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>

        <!-- ✅ DROPDOWN KATEGORI -->
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori Menu</label>
            <select class="form-select" id="kategori" name="kategori" required>
                <option value="pizza">Pizza</option>
                <option value="snacks">Snacks</option>
                <option value="drinks">Drinks</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="text" class="form-control" id="harga" name="harga" required placeholder="contoh: 100.000 atau Rp 100.000">
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Foto Menu</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-custom-red">
            <i class="fas fa-save"></i> Simpan Menu
        </button>
        <a href="manage_menu.php" class="btn btn-secondary">Batal</a>

    </form>
</div>

<?php include 'admin_footer.php'; ?>
