<?php
include 'admin_header.php';

$message = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Ambil data menu yang akan di-edit
    $stmt = $conn->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_menu = $result->fetch_assoc();
    $stmt->close();

    if (!$data_menu) {
        $message = "<div class='alert alert-danger'>Menu tidak ditemukan.</div>";
        $id = 0; // Invalid ID
    }
}

// Proses Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && $id > 0) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $harga = (float)$_POST['harga'];
    $gambar_lama = $data_menu['gambar'];
    $gambar_nama = $gambar_lama;

    // Proses Upload Gambar Baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../images/";
        $file_ext = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $gambar_nama = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $gambar_nama;
        
        if (getimagesize($_FILES["gambar"]["tmp_name"]) && in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Hapus gambar lama jika berhasil upload yang baru
                if ($gambar_lama && file_exists('../images/' . $gambar_lama)) {
                    unlink('../images/' . $gambar_lama);
                }
            } else {
                $message = "<div class='alert alert-danger'>Gagal mengupload file gambar.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>File bukan gambar atau format tidak diizinkan.</div>";
        }
    }

    if ($message == '') {
        // Update ke Database
        $sql = "UPDATE menu SET nama_pizza=?, deskripsi=?, harga=?, gambar=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $nama, $deskripsi, $harga, $gambar_nama, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<div class='alert alert-success'>Menu **" . htmlspecialchars($nama) . "** berhasil diperbarui.</div>";
            header("location: manage_menu.php");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<h2 class="mb-4 text-warning"><i class="fas fa-edit"></i> Edit Menu: <?php echo htmlspecialchars($data_menu['nama_pizza'] ?? 'Item'); ?></h2>
<?php echo $message; ?>

<?php if ($id > 0): ?>
<div class="card shadow-lg p-4">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Pizza</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($data_menu['nama_pizza']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php echo htmlspecialchars($data_menu['deskripsi']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" step="any" value="<?php echo htmlspecialchars($data_menu['harga']); ?>" required>
        </div>
        
        <h5 class="mt-4 mb-3 text-muted">Update Foto (Opsional)</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="gambar" class="form-label">Pilih Foto Baru</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
            </div>
            <div class="col-md-6 text-center">
                <p>Foto Saat Ini:</p>
                <img src="../images/<?php echo htmlspecialchars($data_menu['gambar']); ?>" width="150" class="img-thumbnail">
            </div>
        </div>

        <button type="submit" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Update Menu</button>
        <a href="manage_menu.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?php endif; ?>

<?php include 'admin_footer.php'; ?>