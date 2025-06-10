<?php
session_start();
include('../koneksi.php');

// if (!isset($_SESSION['username'])) {
//     header("location:login.php?pesan=logindulu");
//     exit;
// }

$id_buku = $_GET['id_buku'];
$id_kategori = $_GET['id_kategori'];
$judul = $_GET['judul'];
$penulis = $_GET['penulis'];
$penerbit = $_GET['penerbit'];
$harga = $_GET['harga'];
$stok = $_GET['stok'];
$tanggal_terbit = $_GET['tanggal_terbit'];
$jumlah_halaman = $_GET['jumlah_halaman'];
$bahasa = $_GET['bahasa'];
$isbn = $_GET['isbn'];
$deskripsi = $_GET['deskripsi'];
$gambar = $_GET['gambar'];
$sql = "UPDATE buku SET judul='$judul', penulis='$penulis', penerbit='$penerbit', harga='$harga', stok='$stok',
        tanggal_terbit='$tanggal_terbit', jumlah_halaman='$jumlah_halaman', bahasa='$bahasa', isbn='$isbn', deskripsi ='$deskripsi',
        gambar='$gambar' WHERE id_buku = '$id_buku'";
$query = mysqli_query($koneksi, $sql);

if($query) {
    header("location:buku.php?edit=sukses");
    exit;
} else {
    header("location:buku.php?edit=gagal");
    exit;
}
?>