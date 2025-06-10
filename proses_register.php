<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$cekUser = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");

if (mysqli_num_rows($cekUser) > 0) {
    echo "<script>alert('Email sudah terdaftar!'); window.location.href='loginregister.php';</script>";
    return;
} else {
    $tambahUser = mysqli_query($koneksi, "INSERT INTO users(username, email, password, role) VALUES ('$username','$email', md5('$password'), 'user')");

    if ($tambahUser) {
        echo "<script>alert('Email berhasil terdaftar!'); window.location.href='loginregister.php';</script>";
        exit;
    } else {
        echo "<script>alert('Email gagal terdaftar!'); window.location.href='loginregister.php';</script>";
        exit;
    }
}
?>
