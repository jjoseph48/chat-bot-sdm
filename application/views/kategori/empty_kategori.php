<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Kosong</title>
    <!-- Kita naikkan versinya ke 5.3.3 agar browser mengunduh ulang file yang tidak korup -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="text-center">
        <h1 class="display-1 text-muted mb-4">📂</h1>
        <h3 class="text-secondary">Belum Ada Data Kategori</h3>
        <p class="text-muted mb-4">Tabel kategori Anda saat ini masih kosong. Silakan tambahkan kategori pertama Anda.</p>
        
        <!-- KUNCI PERUBAHAN 1: Kita ganti data-bs-toggle dengan onclick JS biasa -->
        <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm" onclick="bukaModal()">
            + Tambah Kategori Pertama
        </button>
    </div>

    <!-- MODAL TAMBAH KATEGORI (Efek 'fade' dihilangkan sementara untuk mencegah bentrok animasi) -->
    <div class="modal" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <!-- KUNCI PERUBAHAN 2: Tag <form> sekarang membungkus seluruh modal-content , site_url untuk memanggil controller -->
            <form action="<?= site_url('kategori/simpan') ?>" method="post" class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" onclick="tutupModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul_kategori" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="tutupModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

<!-- Load JS versi terbaru -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- KUNCI PERUBAHAN 3: Kita panggil Modal menggunakan Javascript Manual -->
<script>
    // Inisialisasi modal secara paksa
    const modalElemen = document.getElementById('tambahModal');
    const myModal = new bootstrap.Modal(modalElemen);

    function bukaModal() {
        myModal.show();
    }

    function tutupModal() {
        myModal.hide();
    }
</script>
</body>
</html>