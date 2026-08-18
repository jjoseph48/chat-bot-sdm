<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Status FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <!-- Tombol + Tambah Status Dihapus -->
            <h5 class="mb-0">Daftar Status FAQ</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Judul Status</th>
                        <!-- Kolom Aksi Dihapus -->
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($status as $s): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $s['judul_status'] ?></td>
                        <!-- Kolom Tombol Edit Dihapus -->
                    </tr>
                    <!-- Modal Edit Dihapus dari sini -->
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>