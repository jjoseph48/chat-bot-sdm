<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot SDM - Bisma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Gaya khusus untuk ruang obrolan */
        .chat-container { height: 65vh; overflow-y: auto; background-color: #f9f9fc; padding: 20px; }
        
        /* Pembungkus Pesan untuk menyelaraskan nama, gelembung, dan waktu */
        .msg-wrapper { display: flex; flex-direction: column; margin-bottom: 20px; }
        .msg-sender { font-size: 0.75rem; color: #6c757d; margin-bottom: 4px; }
        .msg-time { font-size: 0.65rem; color: #adb5bd; margin-top: 4px; }
        
        /* Gelembung Pesan Dasar */
        .msg-bubble { max-width: 80%; padding: 12px 18px; font-size: 0.95rem; position: relative; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: pre-wrap; word-wrap: break-word; }
        
        /* Pesan dari User */
        .user-wrapper { align-items: flex-end; }
        .user-wrapper .msg-bubble { background-color: #0d6efd; color: white; border-radius: 18px 18px 4px 18px; }
        
        /* Pesan dari Bisma (Bot) */
        .bot-wrapper { align-items: flex-start; }
        .bot-wrapper .msg-bubble { background-color: #ffffff; color: #333; border: 1px solid #e2e3e5; border-radius: 18px 18px 18px 4px; }
        
        .typing-indicator { color: #6c757d; font-size: 0.9em; font-style: italic; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                
                <!-- Bagian Header (Diperbarui dengan titik hijau dan nama Bisma) -->
                <div class="card-header bg-white border-bottom p-3 d-flex align-items-center">
                    <div class="position-relative me-3">
                        <div class="bg-primary text-white d-flex justify-content-center align-items-center rounded-circle" style="width: 45px; height: 45px; font-size: 1.5rem;">
                            🤖
                        </div>
                        <!-- Titik Hijau Online -->
                        <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 14px; height: 14px;"></span>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem;">Bisma</h5>
                        <small class="text-muted d-block" style="font-size: 0.85rem;">Online</small>
                    </div>
                </div>

                <!-- Bagian Layar Chat -->
                <div class="card-body chat-container" id="chatBox">
                    <!-- Pesan akan diinjeksi via JavaScript agar timestamp otomatis akurat -->
                </div>

                <!-- Bagian Input Form -->
                <div class="card-footer bg-white py-3 border-top">
                    <form id="chatForm" class="d-flex">
                        <input type="text" id="pesanInput" class="form-control me-2 rounded-pill bg-light border-0 px-4" placeholder="Send a message..." autocomplete="off" required>
                        <button type="submit" class="btn text-primary fs-5 border-0">
                            ➤
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const chatForm = document.getElementById('chatForm');
    const pesanInput = document.getElementById('pesanInput');
    const chatBox = document.getElementById('chatBox');

    // Fungsi mendapatkan jam dan menit saat ini
    function dapatkanWaktuSekarang() {
        const sekarang = new Date();
        return sekarang.getHours().toString().padStart(2, '0') + ':' + 
               sekarang.getMinutes().toString().padStart(2, '0');
    }

    // Fungsi untuk mencetak kotak pesan baru beserta struktur nama dan waktu
    function tambahPesanKeLayar(pengirim, teks) {
        const wrapperDiv = document.createElement('div');
        const nama = (pengirim === 'user') ? 'Pegawai' : 'Bisma';
        
        wrapperDiv.className = (pengirim === 'user') ? 'msg-wrapper user-wrapper' : 'msg-wrapper bot-wrapper';
        
        // Render HTML struktur pesan
        wrapperDiv.innerHTML = `
            <span class="msg-sender">${nama}</span>
            <div class="msg-bubble">${teks}</div>
            <span class="msg-time">${dapatkanWaktuSekarang()}</span>
        `;
        
        chatBox.appendChild(wrapperDiv);
        chatBox.scrollTop = chatBox.scrollHeight; 
        
        // Mengembalikan elemen gelembungnya (bubble) agar teks di dalamnya bisa diedit nanti
        return wrapperDiv.querySelector('.msg-bubble'); 
    }

    // Pesan sambutan otomatis saat halaman pertama dimuat
    document.addEventListener("DOMContentLoaded", function() {
        tambahPesanKeLayar('bot', 'Halo! Saya Bisma, asisten virtual SDM Anda. Ada yang bisa saya bantu terkait aturan, fasilitas, atau informasi kepegawaian lainnya?');
    });

    // Aksi saat tombol kirim ditekan
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const teksPesan = pesanInput.value.trim();
        if (!teksPesan) return;

        // 1. Tampilkan pesan user
        tambahPesanKeLayar('user', teksPesan);
        pesanInput.value = '';

        // 2. Tampilkan indikator "Mengetik..."
        const loadingElemen = tambahPesanKeLayar('bot', 'Mengetik jawaban...');
        loadingElemen.classList.add('typing-indicator');

        // 3. Kirim data ke API Chatbot
        fetch('<?= site_url('api_chatbot/balas_pesan') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'pesan=' + encodeURIComponent(teksPesan)
        })
        .then(response => response.json())
        .then(data => {
            loadingElemen.classList.remove('typing-indicator'); 
            loadingElemen.textContent = data.balasan;
        })
        .catch(error => {
            loadingElemen.classList.remove('typing-indicator');
            loadingElemen.textContent = 'Maaf, terjadi kesalahan koneksi ke server NLP.';
            console.error('Error Fetching Data:', error);
        });
    });
</script>

</body>
</html>