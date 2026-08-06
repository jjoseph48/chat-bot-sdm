<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Kosong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center">
        <!-- Anda bisa mengganti ini dengan tag <img> untuk memasukkan ilustrasi -->
        <h1 class="display-1 text-muted mb-4">📂</h1>
        <h3 class="text-secondary">Belum Ada Data Status</h3>
        <p class="text-muted mb-4">Tabel status Anda saat ini masih kosong. Silakan tambahkan status pertama Anda.</p>
        
        <!-- Tombol ini memicu Modal Tambah yang sama -->
        <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm" onclick="bukaModal()">
            + Tambah Status Pertama
        </button>
    </div>

    <!-- Modal Tambah Status -->
    <div class="modal" id="tambahModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= site_url('status/simpan') ?>" method="post" class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Status Baru</h5>
                    <button type="button" class="btn-close" onclick="tutupModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Status <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul_status" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="tutupModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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