<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['shipping_method']) && isset($data['shipping_cost'])) {
    // Ambil detail metode pengiriman dari database
    $id_metode = (int)$data['shipping_method'];
    $query = mysqli_query($koneksi, "SELECT * FROM metode_pengiriman WHERE id_metodePengiriman = $id_metode");
    $metode = mysqli_fetch_assoc($query);
    
    if ($metode) {
        $_SESSION['checkout_data']['ongkir'] = [
            'id_metodePengiriman' => $metode['id_metodePengiriman'],
            'nama_metode' => $metode['nama_metode'],
            'biaya' => $metode['biaya'],
            'estimasi' => $metode['estimasi'],
            'deskripsi' => $metode['deskripsi']
        ];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Invalid shipping method']);
    }
} else {
    echo json_encode(['error' => 'Invalid data']);
}
?>