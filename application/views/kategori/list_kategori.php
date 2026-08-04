<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori FAQ</title>
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
            <h5 class="mb-0">Daftar Kategori FAQ</h5>
            <button type="button" class="btn btn-light btn-sm fw-fold" data-bs-toggle="modal" data-bs-target="#tambahModal">
                + Tambah Kategori
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Judul Kategori</th>
                        <th width="15%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($kategori as $k):?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $k['judul_kategori'] ?></td>
                        <td class="text-center">
                            <!-- Menampilkan status hasil JOIN dari Model -->
                             <span class="badge bg-info"><?= $k['judul_status'] ?></span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $k['id_faq_kategori'] ?>">Edit</button>
                            <!-- Tombol Hapus (Soft Delete) -->
                             <a href="<?= site_url('kategori/hapus/'.$k['id_faq_kategori']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                    </tr>
                </tbody>

</html>