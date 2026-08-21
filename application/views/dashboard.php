<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">👋 Selamat Datang, Admin!</h2>
            <p class="text-muted">Ini adalah ringkasan data Pusat Bantuan (FAQ) Anda saat ini.</p>
        </div>
    </div>

    <div class="row">
        <!-- Card Total FAQ -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-2">Total FAQ Aktif</h6>
                        <h2 class="display-4 fw-bold mb-0"><?= $total_faq ?></h2>
                    </div>
                    <div class="display-1 opacity-50">💬</div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= site_url('faq_detail') ?>" class="text-white text-decoration-none small">Kelola FAQ ➔</a>
                </div>
            </div>
        </div>

        <!-- Card Total Kategori -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 bg-success text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-2">Kategori Tersedia</h6>
                        <h2 class="display-4 fw-bold mb-0"><?= $total_kategori ?></h2>
                    </div>
                    <div class="display-1 opacity-50">📂</div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= site_url('kategori') ?>" class="text-white text-decoration-none small">Kelola Kategori ➔</a>
                </div>
            </div>
        </div>

        <!-- Card Total Tag -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 bg-warning text-dark h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-2">Tag Digunakan</h6>
                        <h2 class="display-4 fw-bold mb-0"><?= $total_tag ?></h2>
                    </div>
                    <div class="display-1 opacity-50">🏷️</div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= site_url('tag') ?>" class="text-dark text-decoration-none small fw-bold">Kelola Tag ➔</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>