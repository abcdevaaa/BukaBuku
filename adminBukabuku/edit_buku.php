<?php
session_start();
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_buku = $_POST['id_buku'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $id_kategori = $_POST['kategori'];
    $isbn = $_POST['isbn'] ?? null;
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $tanggal_terbit = $_POST['tanggal_terbit'] ?? null;
    $jumlah_halaman = $_POST['jumlah_halaman'] ?? null;
    $penerbit = $_POST['penerbit'] ?? null;
    $bahasa = $_POST['bahasa'] ?? 'Indonesia';
    $deskripsi = $_POST['deskripsi'] ?? null;

    // Proses upload gambar jika ada
    $gambar = null;
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $file = $_FILES['cover'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $gambar_name = uniqid() . '.' . $ext;
        $target = '../image/' . $gambar_name;
        
        if (move_uploaded_file($file['tmp_name'], $target)) {
            $gambar = $gambar_name;
            
            // Hapus gambar lama jika ada
            $query = mysqli_query($koneksi, "SELECT gambar FROM buku WHERE id_buku = '$id_buku'");
            $old_image = mysqli_fetch_assoc($query)['gambar'];
            if ($old_image && file_exists('../image/' . $old_image)) {
                unlink('../image/' . $old_image);
            }
        }
    }

    // Update data buku
    $query = "UPDATE buku SET 
              judul = '$judul',
              penulis = '$penulis',
              id_kategori = '$id_kategori',
              isbn = " . ($isbn ? "'$isbn'" : "NULL") . ",
              harga = '$harga',
              stok = '$stok',
              tanggal_terbit = " . ($tanggal_terbit ? "'$tanggal_terbit'" : "NULL") . ",
              jumlah_halaman = " . ($jumlah_halaman ? "'$jumlah_halaman'" : "NULL") . ",
              penerbit = " . ($penerbit ? "'$penerbit'" : "NULL") . ",
              bahasa = '$bahasa',
              deskripsi = " . ($deskripsi ? "'$deskripsi'" : "NULL");
    
    if ($gambar) {
        $query .= ", gambar = '$gambar'";
    }
    
    $query .= " WHERE id_buku = '$id_buku'";

    if (mysqli_query($koneksi, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>