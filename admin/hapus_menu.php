<?php
// ... (Proteksi dan include)

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ambil nama file gambar untuk dihapus dari server
    $stmt = $conn->prepare("SELECT gambar FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $gambar_file = $row['gambar'];
    
    // Hapus data dari database
    $sql = "DELETE FROM menu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Hapus file gambar dari folder
        if (file_exists('../images/' . $gambar_file)) {
            unlink('../images/' . $gambar_file);
        }
        header("location: manage_menu.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>