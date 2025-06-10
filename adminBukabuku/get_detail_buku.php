<?php
include '../koneksi.php'; // atau sesuaikan path-nya

$id_buku = $_GET['id_buku'];
$query = mysqli_query($koneksi, "
    SELECT buku.*, kategori.nama_kategori 
    FROM buku 
    JOIN kategori ON buku.id_kategori = kategori.id_kategori 
    WHERE buku.id_buku = '$id_buku'");

$data = mysqli_fetch_assoc($query);

// Format harga
$data['harga'] = (float)$data['harga'];
// Format untuk tampilan (tambahkan field terpisah jika perlu)
$data['harga_formatted'] = 'Rp' . number_format($data['harga'], 0, ',', '.');

// Tentukan status stok
if ($data['stok'] == 0) {
    $data['status'] = '<span class="status-badge status-out-of-stock">Habis</span>';
} elseif ($data['stok'] <= 5) {
    $data['status'] = '<span class="status-badge status-limited">Stok Sedikit</span>';
} else {
    $data['status'] = '<span class="status-badge status-available">Tersedia</span>';
}

// Kirim sebagai JSON
echo json_encode($data);
?>
