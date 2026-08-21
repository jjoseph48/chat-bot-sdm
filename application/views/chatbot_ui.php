<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot SDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Gaya khusus untuk ruang obrolan */
        .chat-container { height: 65vh; overflow-y: auto; background-color: #f8f9fa; padding: 20px; }
        .pesan-user { background-color: #d1e7dd; padding: 12px 18px; border-radius: 15px 15px 0 15px; margin-bottom: 15px; margin-left: auto; max-width: 80%; width: fit-content; text-align: right; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .pesan-bot { background-color: #ffffff; padding: 12px 18px; border-radius: 15px 15px 15px 0; margin-bottom: 15px; margin-right: auto; max-width: 80%; width: fit-content; border: 1px solid #e2e3e5; white-space: pre-wrap; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .typing-indicator { color: #6c757d; font-size: 0.9em; font-style: italic; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                
                <!-- Bagian Header -->
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">🤖 Chatbot SDM</h5>
                    <small>Online - Siap membantu Anda</small>
                </div>

                <!-- Bagian Layar Chat -->
                <div class="card-body chat-container" id="chatBox">
                    <div class="pesan-bot">Halo! Saya asisten virtual SDM Anda. Ada yang bisa saya bantu terkait aturan, fasilitas, atau informasi kepegawaian lainnya?</div>
                </div>

                <!-- Bagian Input Form -->
                <div class="card-footer bg-white py-3">
                    <form id="chatForm" class="d-flex">
                        <input type="text" id="pesanInput" class="form-control me-2 rounded-pill" placeholder="Ketik pertanyaan Anda di sini..." autocomplete="off" required>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Kirim</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Menangkap elemen HTML
    const chatForm = document.getElementById('chatForm');
    const pesanInput = document.getElementById('pesanInput');
    const chatBox = document.getElementById('chatBox');

    // Fungsi untuk mencetak kotak pesan baru ke layar
    function tambahPesanKeLayar(pengirim, teks) {
        const div = document.createElement('div');
        div.className = (pengirim === 'user') ? 'pesan-user' : 'pesan-bot';
        div.textContent = teks;
        chatBox.appendChild(div);
        
        // Otomatis gulir ke pesan paling bawah
        chatBox.scrollTop = chatBox.scrollHeight; 
        return div; // Mengembalikan elemen yang baru dibuat
    }

    // Aksi saat tombol kirim ditekan
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah halaman refresh
        
        const teksPesan = pesanInput.value.trim();
        if (!teksPesan) return;

        // 1. Tampilkan pesan user
        tambahPesanKeLayar('user', teksPesan);
        pesanInput.value = ''; // Kosongkan input

        // 2. Tampilkan indikator "Mengetik..."
        const loadingElemen = tambahPesanKeLayar('bot', 'Mengetik jawaban...');
        loadingElemen.classList.add('typing-indicator');

        // 3. Kirim data ke API Chatbot yang sudah kita buat
        fetch('<?= site_url('api_chatbot/balas_pesan') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'pesan=' + encodeURIComponent(teksPesan)
        })
        .then(response => response.json())
        .then(data => {
            // Hapus efek miring (italic)
            loadingElemen.classList.remove('typing-indicator'); 
            
            // Timpa teks "Mengetik..." dengan jawaban asli dari algoritma TF-IDF
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