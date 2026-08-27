<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container mt-5">
    
    <!-- Notifikasi Flashdata -->
    <?php if($this->session->flashdata('sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('sukses'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pertanyaan FAQ</h5>
            <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#tambahModal">
                + Tambah FAQ
            </button>
        </div>
        <div class="card-body">
            <!-- Barisan Tombol Import & Unduh -->
            <div class="d-flex mb-3">
                <a href="<?= site_url('faq_detail/unduh_template') ?>" class="btn btn-outline-info me-3">
                    📥 Unduh Template
                </a>
                
                <form action="<?= site_url('faq_detail/import_excel') ?>" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
                    <input type="file" name="file_excel" class="form-control me-2" accept=".xlsx" required style="width: 250px;">
                    <button type="submit" class="btn btn-success">
                        📤 Import
                    </button>
                </form>
            </div>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%">Kategori</th>
                        <th>Tag</th>
                        <th>Pertanyaan</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($faq as $f): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <!-- Mengambil judul kategori dari hasil JOIN -->
                        <td><span class="badge bg-secondary"><?= $f['judul_kategori'] ?></span></td>
                        <!-- Menampilkan Kumpulan Tag -->
                        <td>
                            <?php if(!empty($f['tags'])): ?>
                                <?php foreach($f['tags'] as $tg): ?>
                                    <span class="badge bg-primary mb-1"><?= $tg['judul_tag'] ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $f['pertanyaan'] ?></td>
                        <td class="text-center"><span class="badge bg-info"><?= $f['judul_status'] ?></span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $f['id_faq_detail'] ?>">Edit</button>
                            <a href="<?= site_url('faq_detail/hapus/'.$f['id_faq_detail']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus FAQ ini?')">Hapus</a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT FAQ -->
                    <div class="modal fade" id="editModal<?= $f['id_faq_detail'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit FAQ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?= site_url('faq_detail/ubah') ?>" method="post">
                                    <div class="modal-body text-start">
                                        <input type="hidden" name="id_faq_detail" value="<?= $f['id_faq_detail'] ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Kategori FAQ <span class="text-danger">*</span></label>
                                            <select name="id_faq_kategori_fk" class="form-select" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                <!-- Looping Kategori untuk form Edit -->
                                                <?php foreach($kategori as $k): ?>
                                                    <option value="<?= $k['id_faq_kategori'] ?>" <?= ($k['id_faq_kategori'] == $f['id_faq_kategori_fk']) ? 'selected' : '' ?>>
                                                        <?= $k['judul_kategori'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label d-block">Tag (Opsional)</label>
                                            <?php 
                                                // Mengekstrak ID Tag yang dimiliki FAQ ini
                                                $id_tag_dimiliki = array_column($f['tags'], 'id_faq_tag'); 
                                            ?>
                                            <select name="faq_tag_id[]" class="form-control select2-multi" multiple="multiple">
                                                <?php foreach($tag_list as $t): ?>
                                                    <option value="<?= $t['id_faq_tag'] ?>" <?= in_array($t['id_faq_tag'], $id_tag_dimiliki) ? 'selected' : '' ?>>
                                                        <?= $t['judul_tag'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="pertanyaan" value="<?= $f['pertanyaan'] ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Jawaban <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="jawaban" rows="5" required><?= $f['jawaban'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH FAQ -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah FAQ Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('faq_detail/simpan') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori FAQ <span class="text-danger">*</span></label>
                        <select name="id_faq_kategori_fk" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <!-- Looping Kategori untuk form Tambah -->
                            <?php foreach($kategori as $k): ?>
                                <option value="<?= $k['id_faq_kategori'] ?>"><?= $k['judul_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Tag (Opsional)</label>
                        <select name="faq_tag_id[]" class="form-control select2-multi" multiple="multiple">
                            <?php foreach($tag_list as $t): ?>
                                <option value="<?= $t['id_faq_tag'] ?>"><?= $t['judul_tag'] ?></option>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Kita gunakan .each() agar berlaku untuk form Tambah maupun form Edit
        $('.select2-multi').each(function() {
            // Mencari elemen modal terdekat yang membungkus form ini
            var modalParent = $(this).closest('.modal');
            
            $(this).select2({
                placeholder: "Cari dan pilih tag...",
                allowClear: true,
                width: '100%',
                // INI KUNCI UTAMANYA: Tempelkan dropdown ke dalam modal terkait
                dropdownParent: modalParent.length ? modalParent : $(document.body)
            });
        });
    });
</script>
</body>
</html>