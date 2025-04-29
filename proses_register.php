<?php
include "koneksi.php";
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "INSERT INTO users(username, email, password) VALUES ('$username','$email', md5('$password'))";
$query = mysqli_query($koneksi,$sql);

if ($query) {
    header ("location:LoginRegister.php?register=sukses");
    exit;
} else {
    header ("location:register.php?register=gagal");
    exit;
}

?>