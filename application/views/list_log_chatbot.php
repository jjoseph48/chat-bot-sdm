<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Interaksi Chatbot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .table th { background-color: #f8f9fa; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table td { vertical-align: middle; font-size: 0.9rem; }
        .msg-cell { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">Bisma Analytics</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('statistik_chatbot') ?>">Statistik</a></li>
                <li class="nav-item"><a class="nav-link active text-primary fw-bold" href="<?= site_url('statistik_chatbot/riwayat') ?>">Riwayat Log</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('chatbot_ui') ?>">Lihat Chatbot</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard') ?>">Kelola FAQ</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Riwayat Interaksi</h3>
            <p class="text-muted mb-0">Daftar lengkap rekam jejak obrolan pegawai dengan Bisma.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="15%">Waktu</th>
                            <th width="25%">Pesan Pegawai</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="25%">FAQ Terpilih (Matched)</th>
                            <th width="10%" class="text-center">Skor Cosine</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($logs)): ?>
                            <?php foreach($logs as $log): ?>
                            <tr>
                                <td class="text-muted small"><?= date('d M Y, H:i', strtotime($log['waktu_interaksi'])) ?></td>
                                <td class="msg-cell fw-bold text-dark" title="<?= htmlspecialchars($log['pesan_user']) ?>">
                                    <?= htmlspecialchars($log['pesan_user']) ?>
                                </td>
                                <td class="text-center">
                                    <?php if($log['status_match'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25">Matched</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25">Gagal</span>
                                    <?php endif; ?>
                                </td>
                                <td class="msg-cell text-muted">
                                    <?= !empty($log['pertanyaan_faq']) ? htmlspecialchars($log['pertanyaan_faq']) : '<em>(Tidak ada kecocokan)</em>' ?>
                                </td>
                                <td class="text-center fw-bold text-primary">
                                    <?= number_format($log['top_1_skor'], 4) ?>
                                </td>
                                <td class="text-center">
                                    <!-- Tombol Pemicu Modal -->
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal<?= $log['id_log'] ?>">
                                        Detail Kandidat
                                    </button>
                                </td>
                            </tr>

                            <!-- MODAL DETAIL JSON TOP-3 -->
                            <div class="modal fade" id="detailModal<?= $log['id_log'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title fw-bold text-dark">Detail Evaluasi NLP</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">Pesan Masuk:</small>
                                                <div class="p-2 bg-primary bg-opacity-10 text-dark rounded border border-primary border-opacity-25">
                                                    "<?= htmlspecialchars($log['pesan_user']) ?>"
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            <small class="text-muted fw-bold d-block mb-2">3 Kandidat FAQ Terbaik (JSON Parsed):</small>
                                            
                                            <ul class="list-group list-group-flush">
                                                <?php 
                                                    // Membedah data JSON menjadi Array PHP
                                                    $kandidat_array = json_decode($log['top_3_kandidat'], true);
                                                    if(is_array($kandidat_array) && !empty($kandidat_array)): 
                                                        foreach($kandidat_array as $index => $kandidat):
                                                ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <span>Kandidat #<?= $index + 1 ?> <small class="text-muted">(ID: <?= $kandidat['id_faq'] ?>)</small></span>
                                                        <span class="badge <?= ($kandidat['skor'] >= 0.25) ? 'bg-success' : 'bg-secondary' ?> rounded-pill">
                                                            Skor: <?= number_format($kandidat['skor'], 4) ?>
                                                        </span>
                                                    </li>
                                                <?php 
                                                        endforeach;
                                                    else: 
                                                ?>
                                                    <li class="list-group-item text-muted text-center px-0">Tidak ada data kandidat tersimpan.</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END MODAL -->

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">Belum ada riwayat interaksi chatbot.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>