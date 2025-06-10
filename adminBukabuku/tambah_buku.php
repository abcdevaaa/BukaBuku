<?php
session_start();
header('Content-Type: application/json');

include('../koneksi.php');

// if (!isset($_SESSION['username'])) {
//     header("location:login.php?pesan=logindulu");
//     exit;
// }
$id_kategori = $_POST['kategori'];
$judul = $_POST['judul'];
$penulis = $_POST['penulis'];
$penerbit = $_POST['penerbit'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$tanggal_terbit = $_POST['tanggal_terbit'];
$jumlah_halaman = $_POST['jumlah_halaman'];
$bahasa = $_POST['bahasa'];
$isbn = $_POST['isbn'];
$deskripsi = $_POST['deskripsi'];

// Proses upload gambar
$gambar = $_FILES['cover']['name'];
$tmp_name = $_FILES['cover']['tmp_name'];

// Folder penyimpanan gambar
$upload = '../image/'; // sesuaikan dengan struktur folder kamu

// Pindahkan file gambar ke folder image/
move_uploaded_file($tmp_name, $upload . $gambar);

$sql = "INSERT INTO buku(id_kategori, judul, penulis, penerbit, harga, stok, tanggal_terbit, jumlah_halaman, bahasa, isbn, deskripsi, gambar) 
        VALUES ('$id_kategori','$judul','$penulis','$penerbit','$harga','$stok','$tanggal_terbit','$jumlah_halaman','$bahasa','$isbn','$deskripsi','$gambar')";
$query = mysqli_query($koneksi,$sql);

if ($query) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($koneksi)]);
}

?>