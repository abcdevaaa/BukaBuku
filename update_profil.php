<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: LoginRegister.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $current_user = $_SESSION['username']; // Username lama (sebelum diupdate)
    
    // Ambil data user saat ini dari database
    $check = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$current_user'");
    $user = mysqli_fetch_assoc($check);
    
    // Default: gunakan foto profil yang sudah ada
    $profil = $user['profil']; 

    // Proses Upload Foto Profil (Jika ada file yang diunggah)**
    if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['photo'];
        
        // Cek tipe file (hanya gambar JPEG/PNG/JPG)
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowed)) {
            header("Location: akunU.php?error=filetype");
            exit();
        }
        
        // Cek ukuran file (maksimal 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            header("Location: akunU.php?error=filesize");
            exit();
        }
        
        // Generate nama file unik (misal: `64a1b2c3d4e5f.jpg`)
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $profil = uniqid() . '.' . $ext;
        $target = "image/" . $profil;
        
        // Pindahkan file ke folder `image/`
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            header("Location: akunU.php?error=upload");
            exit();
        }
        
        // Hapus foto profil lama (jika ada)
        if (!empty($user['profil']) && file_exists("image/" . $user['profil'])) {
            unlink("image/" . $user['profil']);
        }
    }

    // Update Data ke Database
    $sql = "UPDATE users SET 
            username = ?, 
            email = ?, 
            profil = ? 
            WHERE username = ?";
    
    $stmt = mysqli_prepare($koneksi, $sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($koneksi));
    }
    
    // Bind parameter (username baru, email baru, foto profil, username lama)
    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $profil, $current_user);
    
    if (mysqli_stmt_execute($stmt)) {
        // Update session dengan username & email baru
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        
        header("Location: akunU.php?success=1"); // Redirect dengan pesan sukses
    } else {
        header("Location: akunU.php?error=database"); // Redirect dengan pesan error
    }
    
    exit();
}

// Jika bukan metode POST, redirect ke halaman profil
header("Location: akunU.php");