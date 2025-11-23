<?php
include 'admin_header.php';

$message = '';

// Logika Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // 1. Ambil nama file gambar
    $stmt = $conn->prepare("SELECT gambar FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $gambar_file = $row['gambar'] ?? null;
    $stmt->close();
    
    // 2. Hapus data dari database
    $sql = "DELETE FROM menu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // 3. Hapus file gambar dari folder
        if ($gambar_file && file_exists('../images/' . $gambar_file)) {
            unlink('../images/' . $gambar_file);
        }
        $message = "<div class='alert alert-success'>Menu berhasil dihapus.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal menghapus menu: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Ambil data menu
$sql = "SELECT * FROM menu ORDER BY id DESC";
$result = $conn->query($sql);
?>

<h2 class="mb-4 text-danger"><i class="fas fa-book"></i> Kelola Menu</h2>
<?php echo $message; ?>

<a href="tambah_menu.php" class="btn btn-custom-red mb-3"><i class="fas fa-plus-circle"></i> Tambah Menu Baru</a>

<div class="table-responsive">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="bg-dark text-white">
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Nama Pizza</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><img src="../images/<?php echo $row['gambar']; ?>" width="60" class="img-thumbnail"></td>
                    <td><?php echo htmlspecialchars($row['nama_pizza']); ?></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 70)) . '...'; ?></td>
                    <td>
                        <a href="edit_menu.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning mb-1"><i class="fas fa-edit"></i> Edit</a>
                        <a href="manage_menu.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus menu ini?');"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </td>
                </tr>
                <?php endwhile;
            } else {
                echo "<tr><td colspan='6' class='text-center'>Belum ada menu.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>