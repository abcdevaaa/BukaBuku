<?php
session_start();

// Simpan role jika perlu arahkan berdasarkan role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Hancurkan session
session_unset();
session_destroy();

// Redirect berdasarkan role
if ($role == 'user') {
    header("Location:index.php");
} else {
    header("Location:LoginRegister.php");
}
exit;
?>
