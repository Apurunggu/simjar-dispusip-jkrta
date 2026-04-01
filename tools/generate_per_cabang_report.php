<?php
/**
 * Generate per-cabang CSV reports from barang_masuk.
 * Usage: php tools/generate_per_cabang_report.php
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cabang;
use App\Models\BarangMasuk;

$reportDir = __DIR__ . '/../storage/reports/per_cabang';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0755, true);
}

$cabangs = Cabang::orderBy('id')->get();
if ($cabangs->isEmpty()) {
    echo "No cabangs found. Exiting.\n";
    exit(0);
}

$summary = [];
foreach ($cabangs as $cabang) {
    $rows = BarangMasuk::where('cabang_id', $cabang->id)->orderBy('tanggal_masuk')->get();
    $safeCode = $cabang->kode ?? $cabang->nama ?? 'cabang';
    $safeCode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $safeCode);
    $filename = sprintf('%s/cabang_%d_%s.csv', $reportDir, $cabang->id, $safeCode);

    $fp = fopen($filename, 'w');
    if ($fp === false) {
        echo "Failed to open file: $filename\n";
        continue;
    }

    fputcsv($fp, ['cabang_id','kode','nama_cabang','nomor_barang','nama_barang','kategori','jumlah','stok','tanggal_masuk']);

    foreach ($rows as $r) {
        $date = $r->tanggal_masuk ? $r->tanggal_masuk->toDateString() : '';
        fputcsv($fp, [
            $cabang->id,
            $cabang->kode,
            $cabang->nama,
            $r->nomor_barang,
            $r->nama_barang,
            $r->kategori,
            $r->jumlah,
            $r->stok,
            $date,
        ]);
    }

    fclose($fp);

    $summary[] = [
        'cabang_id' => $cabang->id,
        'file' => realpath($filename) ?: $filename,
        'rows' => count($rows),
    ];

    echo "Wrote: {$summary[count($summary)-1]['file']} ({$summary[count($summary)-1]['rows']} rows)\n";
}

echo "\nReport generation complete. Files are in: $reportDir\n";

exit(0);
