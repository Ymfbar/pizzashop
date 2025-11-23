<?php
include 'admin_header.php';

$message = '';

// Logika Update Status
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = $conn->real_escape_string($_GET['status']);

    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Status pesanan ID: <b>$id</b> berhasil diubah menjadi <b>$status</b>.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal mengubah status: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Ambil data orders
$sql = "SELECT * FROM orders ORDER BY tanggal_pesan DESC";
$result = $conn->query($sql);

function getStatusBadge($status) {
    switch ($status) {
        case 'Pending': return '<span class="badge bg-warning text-dark">Pending</span>';
        case 'Diproses': return '<span class="badge bg-info">Diproses</span>';
        case 'Selesai': return '<span class="badge bg-success">Selesai</span>';
        case 'Batal': return '<span class="badge bg-danger">Batal</span>';
        default: return '<span class="badge bg-secondary">Unknown</span>';
    }
}
?>

<h2 class="mb-4 text-success"><i class="fas fa-receipt"></i> Kelola Pesanan Pelanggan</h2>
<?php echo $message; ?>

<div class="table-responsive" style="overflow: visible; position: relative;">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="bg-dark text-white">
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Total</th>
                <th>Status</th>
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
                    <td><?php echo date('d M H:i', strtotime($row['tanggal_pesan'])); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_pelanggan']); ?></td>
                    <td><?php echo htmlspecialchars($row['telepon']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['alamat'], 0, 30)) . '...'; ?></td>
                    <td class="fw-bold">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td><?php echo getStatusBadge($row['status']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#detail-<?php echo $row['id']; ?>">
                            Detail
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                Ubah Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?action=update&id=<?php echo $row['id']; ?>&status=Pending">Pending</a></li>
                                <li><a class="dropdown-item" href="?action=update&id=<?php echo $row['id']; ?>&status=Diproses">Diproses</a></li>
                                <li><a class="dropdown-item" href="?action=update&id=<?php echo $row['id']; ?>&status=Selesai">Selesai</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?action=update&id=<?php echo $row['id']; ?>&status=Batal">Batal</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>

                <!-- DETAIL PESANAN + BUKTI PEMBAYARAN -->
                <tr class="collapse" id="detail-<?php echo $row['id']; ?>">
                    <td colspan="8" class="bg-light">
                        <p class="mb-1 fw-bold">Detail Pesanan:</p>
                        <pre style="white-space: pre-wrap; font-size: 0.9em;"><?php echo htmlspecialchars($row['detail_pesanan']); ?></pre>

                        <p class="mb-1 fw-bold mt-3">Alamat Lengkap:</p>
                        <p style="font-size: 0.9em;"><?php echo htmlspecialchars($row['alamat']); ?></p>

                        <?php if (!empty($row['bukti_pembayaran'])): ?>
                            <p class="fw-bold mt-3">Bukti Pembayaran:</p>
                            <img src="../uploads/<?php echo $row['bukti_pembayaran']; ?>" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 250px; border: 2px solid #ddd;">
                        <?php else: ?>
                            <p class="text-muted mt-3"><i>Tidak ada bukti pembayaran.</i></p>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php endwhile;
            } else {
                echo "<tr><td colspan='8' class='text-center'>Belum ada pesanan baru.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>
