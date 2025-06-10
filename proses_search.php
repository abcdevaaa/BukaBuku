<?php


$sql = "SELECT * FROM buku WHERE judul LIKE '%$q%' OR penulis LIKE '%$q%'";
$result = mysqli_query($koneksi, $sql);

if(mysqli_num_rows($result) > 0){
  while($buku = mysqli_fetch_assoc($result)){
    $judul = $buku['judul'];
    $penulis = $buku['penulis'];
    
  }
 
} else{

}

?>


  <h2 class="title-card">Rekomendasi Untukmu</h2>
    <div class="wrapper-card">
        <?php if(mysqli_num_rows($result) > 0){ ?>
            <?php while ($buku = mysqli_fetch_assoc($query)) { ?>
                <a href="detail.php?id_buku=<?= $buku['id_buku'] ?>">
            <div class="card">
                <img src="image/<?= $buku['gambar'] ?>" alt="<?= $buku['judul'] ?>">
                <p><small><?= $buku['penulis'] ?></small></p>
                <p><?= $buku['judul'] ?></p>
                <p class="card-price">Rp <?= number_format($buku['harga'], 0, ',', '.') ?></p>
            </div>
            <?php } ?>
        <?php } ?>
        </a>
    </div>