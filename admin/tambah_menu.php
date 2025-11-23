<?php
include 'admin_header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $harga = (float)$_POST['harga'];
    
    // Proses Upload Gambar
    $gambar_nama = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../images/";
        $file_ext = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $gambar_nama = uniqid() . '.' . $file_ext; // Unique ID untuk nama file
        $target_file = $target_dir . $gambar_nama;
        
        // Cek tipe file dan ukuran, lalu pindahkan
        if (getimagesize($_FILES["gambar"]["tmp_name"]) && in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Berhasil upload
            } else {
                $message = "<div class='alert alert-danger'>Gagal mengupload file gambar.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>File bukan gambar atau format tidak diizinkan.</div>";
        }
    } else {
         $message = "<div class='alert alert-danger'>Gambar wajib diupload.</div>";
    }

    if ($message == '') {
        // Insert ke Database
        $sql = "INSERT INTO menu (nama_pizza, deskripsi, harga, gambar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $nama, $deskripsi, $harga, $gambar_nama);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<div class='alert alert-success'>Menu **" . htmlspecialchars($nama) . "** berhasil ditambahkan.</div>";
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
            <label for="nama" class="form-label">Nama Pizza</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" step="any" required>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Foto Menu</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-custom-red"><i class="fas fa-save"></i> Simpan Menu</button>
        <a href="manage_menu.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include 'admin_footer.php'; ?>