<?php
include "koneksi.php";
session_start();
$_SESSION['id_users'] = 40; // Hardcode untuk testing

$query = mysqli_query($koneksi, "SELECT * FROM alamat WHERE id_users = 40 LIMIT 1");
$data = mysqli_fetch_assoc($query);

echo "<h1>Test Tampil Alamat</h1>";
echo "<pre>";
print_r($data);
echo "</pre>";

echo "<div style='border:2px solid green;padding:10px;'>";
echo "<p><strong>".htmlspecialchars($data['nama_penerima'])."</strong></p>";
echo "<p>".htmlspecialchars($data['no_telepon'])."</p>";
echo "</div>";
?>