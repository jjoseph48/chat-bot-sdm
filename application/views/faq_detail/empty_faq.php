<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-widt, initial-scale=1.0">
    <title>FAQ Kosong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="text-center">
        <h1 class="display-1 text-muted mb-4">💬</h1>
        <h3 class="text-secondary">Belum Ada Data FAQ</h3>
        <p class="text-muted mb-4">Pusat bantuan Anda saat ini masih kosong. Silakan buat pertanyaan pertama.</p>

        <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm" onclick="bukaModal()">
            + Tambah FAQ Pertama
        </button>
    </div>

    <!-- Modal Tambah FAQ Pertama -->
    <div class="modal" id="tambahModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="<?= site_url('faq_detail/simpan') ?>" method="post" class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah FAQ Baru</h5>
                    <button type="button" class="btn-close" onclick="tutupModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori FAQ<span class="text-danger">*</span></label>
                        <select name="id_faq_kategori_fk" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                            <!-- Looping Kategori -->
                            <?php foreach($kategori as $k): ?>
                                <option value="<?= $k['id_faq_kategori'] ?>"><?= $k['judul_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pertanyaan" required placeholder="Tuliskan pertanyaan...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="jawaban" rows="5" required placeholder="Tuliskan jawaban detail di sini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="tutupModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalElemen = document.getElementById('tambahModal');
    const myModal = new bootstrap.Modal(modalElemen);

    function bukaModal() { myModal.show(); }
    function tutupModal() { myModal.hide(); }
</script>
</body>
</html>


