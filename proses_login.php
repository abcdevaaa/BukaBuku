<?php
session_start();
include "koneksi.php";
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = '$email' AND password = md5('$password')";
$query = mysqli_query($koneksi, $sql);


if (mysqli_num_rows($query) == 1) {
    $users = mysqli_fetch_assoc($query);
    $_SESSION['id_users'] = $users['id_users'];
    $_SESSION['username'] = $users['username'];
    $_SESSION['email'] = $users['email'];

    header("location:index.php?login=sukses");
    exit;
} else {
    header("location:index.php?login=gagal");
    exit;
}
?>