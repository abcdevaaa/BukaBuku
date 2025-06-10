<?php
session_start();
include "koneksi.php";
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = '$email' AND password = md5('$password')";
$query = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($query) == 1) {

        $dataUser = mysqli_fetch_assoc($query);
            
            if($dataUser['role'] == "user"){
                    
                    $_SESSION['id_users'] = $dataUser['id_users'];
                    $_SESSION['username'] = $dataUser['username'];
                    $_SESSION['email'] = $dataUser['email'];
                    $_SESSION['role'] = $dataUser['role'];
                    $_SESSION['role'] = $dataUser['role'];

    
                    header("location:index.php?login=sukses");
                    exit;
    
            } else if($dataUser['role'] == "admin") {
    
                    $_SESSION['id_users'] = $dataUser['id_users'];
                    $_SESSION['username'] = $dataUser['username'];
                    $_SESSION['email'] = $dataUser['email'];
                    $_SESSION['role'] = $dataUser['role'];

    
                    header("location:adminBukabuku/dashboard.php?login=sukses");
                    exit;
                
            } else {
    
                header("location:index.php?login=gagal");
                        exit;
            
            }
        }
    
    



?>