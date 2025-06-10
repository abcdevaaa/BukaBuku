<?php
include '../koneksi.php';

$query = mysqli_query($koneksi, "SELECT id_kategori, nama_kategori FROM kategori");

$kategori = [];
while ($row = mysqli_fetch_assoc($query)) {
    $kategori[] = $row;
}

echo json_encode($kategori);
?>
