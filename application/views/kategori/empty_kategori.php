<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Kosong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center">
        <!-- Anda bisa mengganti ini dengan tag <img> untuk memasukkan ilustrasi -->
        <h1 class="display-1 text-muted mb-4">📂</h1>
        <h3 class="text-secondary">Belum Ada Data Kategori</h3>
        <p class="text-muted mb-4">Tabel kategori Anda saat ini masih kosong. Silakan tambahkan kategori pertama Anda.</p>
        
        <!-- Tombol ini memicu Modal Tambah yang sama -->
        <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-targets="#tambahModal">
            + Tambah Kategori Pertama
        </button>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= site_url('kategori/simpan') ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul_kategori" required>
                        </div>
                    </div>
                </form>
            </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>