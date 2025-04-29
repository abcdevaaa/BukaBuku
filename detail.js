document.getElementById('favorite-button').addEventListener('click', function() {
    const heartIcon = this.querySelector('i');
    
    // Toggle class untuk mengubah status favorit
    heartIcon.classList.toggle('fas'); // Mengubah ke solid heart
    heartIcon.classList.toggle('far'); // Mengubah ke regular heart

    // Ubah teks tombol
    if (heartIcon.classList.contains('fas')) {
        this.textContent = ' Favorit'; // Jika sudah favorit
        this.prepend(heartIcon); // Menambahkan icon ke tombol
    } else {
        this.textContent = ' Favorit'; // Jika tidak favorit
        this.prepend(heartIcon); // Menambahkan icon ke tombol
    }
});
document.querySelector('.read-more').addEventListener('click', function() {
    const fullContent = document.querySelector('.full-content');
    if (fullContent.style.display === 'none') {
        fullContent.style.display = 'block';
        this.textContent = 'Tutup'; // Ubah teks tombol menjadi "Tutup"
    } else {
        fullContent.style.display = 'none';
        this.textContent = 'Baca Selengkapnya'; // Kembalikan teks tombol
    }
});
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const cartItem = this.closest('.cart'); // Mengambil elemen cart terdekat
        const title = cartItem.querySelector('.title').textContent;
        const price = cartItem.querySelector('.price').textContent;

        // Logika untuk menambahkan item ke keranjang
        console.log(`Menambahkan ${title} ke keranjang dengan harga ${price}`);
        alert(`${title} telah ditambahkan ke keranjang!`);
    });
});