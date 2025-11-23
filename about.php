<?php include 'includes/header.php'; ?>

<style>
    /* Mengatur pola background untuk menutupi seluruh halaman */
    body {
        /* Set pattern background */
        background-image: url('images/pattern.png');
        background-size: cover;
    }

    /* Membuat dark overlay untuk seluruh halaman */
    body::before {
        content: "";
        position: fixed; /* Use fixed so it covers the whole screen regardless of scroll */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.45); /* Your desired dark overlay color */
        z-index: -1; /* Posisikan di bawah konten, tapi di atas background body */
    }

    /* Styles for the content container */
    .bg-overlay {
        /* Hapus min-height yang lama, biarkan konten menentukan tingginya */
        /* display: flex, flex-direction, justify-content, padding tetap sama */
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 40px 0;
        min-height: calc(100vh - 150px); /* Boleh dipertahankan jika ingin konten setinggi viewport */
    }

    /* Biar card lebih panjang */
    .card {
        min-height: 330px;
    }
</style>
<div class="container">
    <div class="bg-overlay">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg p-4 h-100">
                    <h4 class="text-danger"><i class="fas fa-utensils"></i> Tentang Pizza Shop</h4>
                    <p>Pizza Shop didirikan pada tahun 2023 dengan visi untuk menyajikan pizza berkualitas premium dengan harga yang terjangkau. Kami percaya bahwa setiap orang berhak menikmati pizza yang lezat dan dibuat dari bahan-bahan segar pilihan.</p>
                    <p>Kami bangga dengan resep adonan rahasia kami yang menghasilkan tekstur renyah di luar dan lembut di dalam. Cobalah sekarang!</p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg p-4 h-100">
                    <h4 class="text-success"><i class="fas fa-headset"></i> Hubungi Kami</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-map-marker-alt text-danger"></i> **Alamat:** Jl. Pizza No. 10, Kota Lezat, Indonesia</li>
                        <li class="list-group-item"><i class="fas fa-phone-alt text-danger"></i> **Telepon:** (021) 1234 5678</li>
                        <li class="list-group-item"><i class="fas fa-envelope text-danger"></i> **Email:** info@pizzashop.com</li>
                        <li class="list-group-item"><i class="fab fa-whatsapp text-danger"></i> **WhatsApp:** 0812-3456-7890</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>