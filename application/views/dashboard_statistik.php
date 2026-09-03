<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik Chatbot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Palet Warna & Styling Khusus Sesuai Desain */
        body { background-color: #1e293b; color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-brand { color: #f8fafc !important; font-weight: bold; }
        .navbar-brand span { color: #eab308; }
        .nav-link { color: #cbd5e1 !important; margin-left: 15px; }
        .nav-link:hover { color: #ffffff !important; }
        
        .card-custom { background-color: #ffffff; color: #0f172a; border-radius: 16px; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .hero-card { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: 1px solid #475569; }
        
        .kpi-icon { width: 40px; height: 40px; background-color: #3b82f6; color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 10px; }
        .kpi-title { font-size: 0.85rem; color: #64748b; font-weight: 600; margin-bottom: 5px; }
        .kpi-value { font-size: 1.8rem; font-weight: bold; color: #1d4ed8; margin-bottom: 0; }
        
        .table-custom th { background-color: #f8fafc; color: #475569; font-size: 0.85rem; padding: 12px; border-bottom: 2px solid #e2e8f0; }
        .table-custom td { font-size: 0.9rem; padding: 12px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

<!-- Navigasi -->
<nav class="navbar navbar-expand-lg pt-4 pb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Panel Admin <span>FAQ Chatbot</span></a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('statistik_chatbot') ?>">Statistik</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('dashboard') ?>">Kelola FAQ</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('chatbot_ui') ?>">Lihat Chatbot</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <!-- Hero Section -->
    <div class="card card-custom hero-card mb-4 p-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill">Admin Dashboard</span>
                <h2 class="fw-bold">Statistik Chatbot</h2>
                <p class="mb-0 text-light opacity-75">Pantau performa percakapan, kualitas matching FAQ, dan tren pertanyaan pengguna.</p>
            </div>
            <div>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-primary px-4 py-2 rounded-pill fw-bold" style="background-color: #6366f1; border: none;">Kelola FAQ</a>
            </div>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-3 h-100">
                <div class="kpi-icon">&#931;</div>
                <div class="kpi-title">Total Chats</div>
                <div class="kpi-value"><?= number_format($kpi['total_chats']) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 h-100">
                <div class="kpi-icon">D</div>
                <div class="kpi-title">Chats Hari Ini</div>
                <div class="kpi-value"><?= number_format($kpi['chats_hari_ini']) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 h-100">
                <div class="kpi-icon">A</div>
                <div class="kpi-title">Rata-rata Skor (Semua)</div>
                <div class="kpi-value"><?= number_format($kpi['avg_skor_semua'], 4) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 h-100">
                <div class="kpi-icon">M</div>
                <div class="kpi-title">Rata-rata Skor (Matched)</div>
                <div class="kpi-value"><?= number_format($kpi['avg_skor_matched'], 4) ?></div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="row g-4">
        <!-- Top 10 User Queries -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100">
                <h6 class="fw-bold mb-3">Top 10 Pertanyaan Pengguna</h6>
                <div class="table-responsive">
                    <table class="table table-custom table-borderless w-100">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Pertanyaan</th>
                                <th width="15%" class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($top_pertanyaan)): ?>
                                <?php $no=1; foreach($top_pertanyaan as $tp): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($tp['pesan_user']) ?></td>
                                    <td class="text-center fw-bold"><?= $tp['jumlah'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">Belum ada data interaksi</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top 10 Matched FAQs -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100">
                <h6 class="fw-bold mb-3">Top 10 FAQ yang Paling Sering Match</h6>
                <div class="table-responsive">
                    <table class="table table-custom table-borderless w-100">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th width="15%">ID FAQ</th>
                                <th>Pertanyaan FAQ</th>
                                <th width="20%" class="text-center">Jumlah Match</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($top_faq_matched)): ?>
                                <?php $no=1; foreach($top_faq_matched as $tf): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $tf['top_1_id_faq'] ?></span></td>
                                    <td><?= htmlspecialchars($tf['pertanyaan']) ?></td>
                                    <td class="text-center fw-bold"><?= $tf['jumlah_match'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data FAQ yang cocok</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>