<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tag FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <!-- Notifikasi Flashdata -->
     <?php if($this->session->flashdata('sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('sukses'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tag FAQ</h5>
            <button type="button" class="btn btn-light btn-sm fw-fold" data-bs-toggle="modal" data-bs-target="#tambahModal">
                + Tambah Tag
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Judul Tag</th>
                        <th width="15%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($tag as $t):?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $t['judul_tag'] ?></td>
                            <td class="text-center">
                                <!-- Menampilkan status hasil JOIN dari Model -->
                                 <span class="badge bg-info"><?= $t['judul_status'] ?></span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $t['id_faq_tag'] ?>">Edit</button>
                                    <!-- Tombol Hapus (Soft Delete) -->
                                    <a href="<?= site_url('tag/hapus/'.$t['id_faq_tag']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>

                            <!-- Modal Edit Tag -->
                            <div class="modal fade" id="editModal<?= $t['id_faq_tag'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Tag</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <form action="<?= site_url('tag/ubah') ?>" method="post">
                                            <div class="modal-body text-start">
                                                <input type="hidden" name="id_faq_tag" value="<?= $t['id_faq_tag'] ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Judul Tag<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="judul_tag" value="<?= $t['judul_tag'] ?>" required>
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

<!-- Modal Tambah Tag -->
 <div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tag Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= site_url('tag/simpan') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Tag<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul_tag" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


