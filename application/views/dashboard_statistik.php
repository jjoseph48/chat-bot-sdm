<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik Chatbot</title>
    <!-- Gunakan CSS utama E-Talenta Anda di sini jika ada -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS diminimalkan agar tidak bentrok dengan tema utama Anda */
        body { background-color: #f4f6f9; }
        .card { border-radius: 12px; }
        .icon-box { 
            width: 48px; height: 48px; 
            border-radius: 10px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 1.25rem; font-weight: bold;
        }
        .table th { background-color: #f8f9fa; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body>

<!-- Navigasi Netral -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">Bisma Analytics</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active fw-bold text-primary" href="<?= site_url('statistik_chatbot') ?>">Statistik</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="<?= site_url('statistik_chatbot/riwayat') ?>">Riwayat Log</a></li>
                
                <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard') ?>">Kelola FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('chatbot_ui') ?>">Lihat Chatbot</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Statistik Chatbot</h3>
            <p class="text-muted mb-0">Pantau performa interaksi asisten virtual SDM.</p>
        </div>
        <a href="<?= site_url('dashboard') ?>" class="btn btn-primary shadow-sm">
            + Kelola Data FAQ
        </a>
    </div>

    <!-- 4 KPI Cards (Light & Clean) -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">&#931;</div>
                    <div>
                        <small class="text-muted fw-bold d-block">Total Chats</small>
                        <h4 class="fw-bold mb-0 text-dark"><?= number_format($kpi['total_chats']) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success me-3">H</div>
                    <div>
                        <small class="text-muted fw-bold d-block">Chats Hari Ini</small>
                        <h4 class="fw-bold mb-0 text-dark"><?= number_format($kpi['chats_hari_ini']) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info me-3">S</div>
                    <div>
                        <small class="text-muted fw-bold d-block">Rata-rata Skor (Semua)</small>
                        <h4 class="fw-bold mb-0 text-dark"><?= number_format($kpi['avg_skor_semua'], 4) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">M</div>
                    <div>
                        <small class="text-muted fw-bold d-block">Skor Rata-rata (Match)</small>
                        <h4 class="fw-bold mb-0 text-dark"><?= number_format($kpi['avg_skor_matched'], 4) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data (Clean Layout) -->
    <div class="row g-4">
        <!-- Top 10 User Queries -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark">Top 10 Pertanyaan Pengguna</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">No</th>
                                    <th>Pertanyaan</th>
                                    <th width="15%" class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($top_pertanyaan)): ?>
                                    <?php $no=1; foreach($top_pertanyaan as $tp): ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++ ?></td>
                                        <td class="text-dark"><?= htmlspecialchars($tp['pesan_user']) ?></td>
                                        <td class="text-center"><span class="badge bg-light text-dark border"><?= $tp['jumlah'] ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data interaksi</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 10 Matched FAQs -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark">Top 10 FAQ (Paling Sering Match)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">No</th>
                                    <th width="15%">ID FAQ</th>
                                    <th>Pertanyaan FAQ</th>
                                    <th width="20%" class="text-center">Match</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($top_faq_matched)): ?>
                                    <?php $no=1; foreach($top_faq_matched as $tf): ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++ ?></td>
                                        <td><span class="badge bg-secondary"><?= $tf['top_1_id_faq'] ?></span></td>
                                        <td class="text-dark"><?= htmlspecialchars($tf['pertanyaan']) ?></td>
                                        <td class="text-center"><span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25"><?= $tf['jumlah_match'] ?> kali</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data FAQ yang cocok</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>