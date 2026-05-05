<?php
/**
 * HTML Dashboard - View All Branches Data
 * Tampilan interaktif data semua cabang
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Cabang;
use App\Models\User;
use App\Models\BarangMasuk;

// Fetch data
$cabangs = Cabang::orderBy('is_pusat', 'desc')->orderBy('nama_cabang')->get();
$allUsers = User::with('role', 'cabang')->get();
$allBarang = BarangMasuk::with('cabang')->orderBy('cabang_id')->get();

// Statistics
$totalCabangs = $cabangs->count();
$totalUsers = $allUsers->count();
$totalBarang = $allBarang->count();
$totalQty = $allBarang->sum('jumlah');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMJAR - Dashboard Semua Cabang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            font-size: 2rem;
            color: #667eea;
            margin: 0;
        }
        .stat-card p {
            color: #666;
            margin: 0.5rem 0 0;
        }
        .cabang-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        .cabang-card.pusat {
            border-left-color: #ffc107;
            background-color: #fffbf0;
        }
        .cabang-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .badge-pusat {
            background-color: #ffc107;
            color: #000;
        }
        .users-list, .barang-list {
            margin-top: 1rem;
        }
        .user-item, .barang-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }
        .user-item:last-child, .barang-item:last-child {
            border-bottom: none;
        }
        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .empty {
            color: #999;
            font-style: italic;
            padding: 0.5rem 0;
        }
        .icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="header">
            <h1><i class="bi bi-diagram-3"></i> SIMJAR - Dashboard Semua Cabang</h1>
            <p class="mb-0">Lihat data lengkap dari semua cabang dalam satu halaman</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="bi bi-building icon" style="color: #667eea;"></i>
                <h3><?php echo $totalCabangs; ?></h3>
                <p>Total Cabang</p>
            </div>
            <div class="stat-card">
                <i class="bi bi-people icon" style="color: #764ba2;"></i>
                <h3><?php echo $totalUsers; ?></h3>
                <p>Total Pengguna</p>
            </div>
            <div class="stat-card">
                <i class="bi bi-box icon" style="color: #f093fb;"></i>
                <h3><?php echo $totalBarang; ?></h3>
                <p>Total Item Barang</p>
            </div>
            <div class="stat-card">
                <i class="bi bi-basket icon" style="color: #4facfe;"></i>
                <h3><?php echo number_format($totalQty); ?></h3>
                <p>Total Kuantitas</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="table-container">
                    <h2 class="mb-4"><i class="bi bi-diagram-3"></i> Detail Per Cabang</h2>
                    
                    <?php foreach ($cabangs as $cabang): ?>
                        <?php 
                            $cabangUsers = $allUsers->where('cabang_id', $cabang->id);
                            $cabangBarang = $allBarang->where('cabang_id', $cabang->id);
                            $totalBarangQty = $cabangBarang->sum('jumlah');
                        ?>
                        <div class="cabang-card <?php echo $cabang->is_pusat ? 'pusat' : ''; ?>">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="cabang-title mb-0">
                                    <i class="bi bi-geo-alt"></i> <?php echo $cabang->nama_cabang; ?>
                                </h4>
                                <?php if ($cabang->is_pusat): ?>
                                    <span class="badge badge-pusat ms-2">⭐ PUSAT</span>
                                <?php endif; ?>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted"><strong>Kode:</strong> <?php echo $cabang->kode_cabang ?? '-'; ?></small><br>
                                    <small class="text-muted"><strong>Kota:</strong> <?php echo $cabang->kota ?? '-'; ?></small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted"><strong>Alamat:</strong> <?php echo $cabang->alamat ?? '-'; ?></small>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Users -->
                                <div class="col-md-6">
                                    <h5><i class="bi bi-people"></i> Pengguna (<?php echo $cabangUsers->count(); ?>)</h5>
                                    <div class="users-list">
                                        <?php if ($cabangUsers->isEmpty()): ?>
                                            <div class="empty">Tidak ada pengguna</div>
                                        <?php else: ?>
                                            <?php foreach ($cabangUsers as $user): ?>
                                                <div class="user-item">
                                                    <strong><?php echo $user->name; ?></strong><br>
                                                    <small class="text-muted"><?php echo $user->email; ?></small>
                                                    <?php if ($user->role): ?>
                                                        <br><span class="badge bg-info role-badge"><?php echo $user->role->label; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Barang Masuk -->
                                <div class="col-md-6">
                                    <h5><i class="bi bi-box"></i> Barang Masuk (<?php echo $cabangBarang->count(); ?>) - Qty: <?php echo number_format($totalBarangQty); ?></h5>
                                    <div class="barang-list">
                                        <?php if ($cabangBarang->isEmpty()): ?>
                                            <div class="empty">Tidak ada barang</div>
                                        <?php else: ?>
                                            <?php foreach ($cabangBarang as $item): ?>
                                                <div class="barang-item">
                                                    <strong><?php echo $item->nama_barang ?? 'N/A'; ?></strong><br>
                                                    <small class="text-muted">
                                                        [<?php echo $item->nomor_barang ?? 'N/A'; ?>] 
                                                        Qty: <?php echo number_format($item->jumlah ?? 0); ?> - 
                                                        <?php echo $item->kategori ?? 'N/A'; ?>
                                                    </small>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="table-container">
            <h2 class="mb-4"><i class="bi bi-table"></i> Ringkasan Data</h2>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Cabang</th>
                        <th class="text-center">Pengguna</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cabangs as $cabang): ?>
                        <?php 
                            $users = $allUsers->where('cabang_id', $cabang->id)->count();
                            $barang = $allBarang->where('cabang_id', $cabang->id);
                            $items = $barang->count();
                            $qty = $barang->sum('jumlah');
                            $status = $items > 0 ? '<span class="badge bg-success">Ada Data</span>' : '<span class="badge bg-secondary">Kosong</span>';
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo $cabang->nama_cabang; ?></strong>
                                <?php if ($cabang->is_pusat): ?>
                                    <span class="badge badge-pusat">PUSAT</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $users; ?></td>
                            <td class="text-center"><?php echo $items; ?></td>
                            <td class="text-center"><?php echo number_format($qty); ?></td>
                            <td class="text-center"><?php echo $status; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="text-center mb-4" style="color: #999; font-size: 0.9rem;">
            <p>Dibuat pada: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
