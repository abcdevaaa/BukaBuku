document.addEventListener("DOMContentLoaded", function() {
    // Elemen-elemen yang diperlukan
    const minusButtons = document.querySelectorAll(".minus");
    const plusButtons = document.querySelectorAll(".plus");
    const removeButtons = document.querySelectorAll(".remove");
    const deleteAllButton = document.querySelector(".delete");
    const selectAllCheckbox = document.getElementById("select-all");
    const checkoutButton = document.querySelector(".checkout");
    const totalPriceElement = document.getElementById("total-price");
    const quantityInputs = document.querySelectorAll(".quantity-input");
  
    // Fungsi untuk mengupdate total harga
    function updateTotal() {
        let total = 0;
        document.querySelectorAll(".cart-item").forEach(item => {
            // Hanya hitung item yang tidak dicentang untuk dihapus
            if (!item.querySelector(".item-checkbox").checked) {
                const priceText = item.querySelector(".price").textContent;
                const price = parseInt(priceText.replace("Rp", "").replace(/\./g, ""));
                const quantity = parseInt(item.querySelector(".quantity-input").value);
                total += price * quantity;
            }
        });
        totalPriceElement.textContent = "Rp" + formatNumber(total);
    }
  
    // Fungsi untuk memformat angka dengan titik sebagai pemisah ribuan
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  
    // Event listener untuk tombol minus
    minusButtons.forEach(button => {
        button.addEventListener("click", function() {
            const input = this.nextElementSibling;
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateTotal();
            }
        });
    });
  
    // Event listener untuk tombol plus
    plusButtons.forEach(button => {
        button.addEventListener("click", function() {
            const input = this.previousElementSibling;
            input.value = parseInt(input.value) + 1;
            updateTotal();
        });
    });
  
    // Event listener untuk input quantity manual
    quantityInputs.forEach(input => {
        input.addEventListener("change", function() {
            if (this.value < 1) {
                this.value = 1;
            }
            updateTotal();
        });
    });
  
    // Event listener untuk tombol hapus per item
    removeButtons.forEach(button => {
        button.addEventListener("click", function() {
            if (confirm("Apakah Anda yakin ingin menghapus item ini?")) {
                this.closest(".cart-item").remove();
                updateTotal();
                checkEmptyCart();
            }
        });
    });
  
    // Event listener untuk checkbox "Select All"
    selectAllCheckbox.addEventListener("change", function() {
        const checkboxes = document.querySelectorAll(".item-checkbox");
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
  
    // Event listener untuk tombol hapus (yang dipilih)
    deleteAllButton.addEventListener("click", function() {
        const selectedItems = document.querySelectorAll(".item-checkbox:checked");
        if (selectedItems.length > 0) {
            if (confirm("Apakah Anda yakin ingin menghapus item yang dipilih?")) {
                selectedItems.forEach(checkbox => {
                    checkbox.closest(".cart-item").remove();
                });
                selectAllCheckbox.checked = false;
                updateTotal();
                checkEmptyCart();
            }
        } else {
            alert("Pilih setidaknya satu item untuk dihapus");
        }
    });
  
    // Event listener untuk tombol checkout
    checkoutButton.addEventListener("click", function() {
        const cartItems = document.querySelectorAll(".cart-item");
        if (cartItems.length > 0) {
            // Simulasikan proses checkout
            this.textContent = "Memproses...";
            this.disabled = true;
            
            setTimeout(() => {
                alert("Terima kasih! Pesanan Anda telah berhasil diproses.");
                // Kosongkan keranjang setelah checkout
                document.querySelector(".cart-container").innerHTML = "";
                updateTotal();
                this.textContent = "Checkout";
                this.disabled = false;
            }, 1500);
        } else {
            alert("Keranjang belanja Anda kosong");
        }
    });
  
    // Event delegation untuk checkbox item
    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("item-checkbox")) {
            const allCheckboxes = document.querySelectorAll(".item-checkbox");
            const allChecked = Array.from(allCheckboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
        }
    });
  
    // Fungsi untuk mengecek apakah keranjang kosong
    function checkEmptyCart() {
        const cartItems = document.querySelectorAll(".cart-item");
        if (cartItems.length === 0) {
            const emptyCartMessage = document.createElement("div");
            emptyCartMessage.className = "empty-cart";
            emptyCartMessage.innerHTML = `
                <i class="fas fa-shopping-cart" style="font-size: 50px; color: #ccc; margin-bottom: 20px;"></i>
                <h3>Keranjang Belanja Kosong</h3>
                <p>Silakan tambahkan produk ke keranjang belanja Anda</p>
            `;
            document.querySelector(".cart-container").appendChild(emptyCartMessage);
            
            // Tambahkan style untuk pesan keranjang kosong
            const style = document.createElement("style");
            style.textContent = `
                .empty-cart {
                    text-align: center;
                    padding: 40px 20px;
                    color: #666;
                }
                .empty-cart h3 {
                    font-size: 1.8rem;
                    margin-bottom: 10px;
                }
                .empty-cart p {
                    font-size: 1.4rem;
                }
            `;
            document.head.appendChild(style);
        } else {
            const emptyCartMessage = document.querySelector(".empty-cart");
            if (emptyCartMessage) {
                emptyCartMessage.remove();
            }
        }
    }
  
    // Inisialisasi total harga saat pertama kali dimuat
    updateTotal();
  });