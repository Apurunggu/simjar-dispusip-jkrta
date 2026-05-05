<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Verifikasi Nama Cabang ===\n\n";

$cabangs = \App\Models\Cabang::orderBy('id')->get(['id', 'nama_cabang', 'kode_cabang']);

foreach($cabangs as $cabang) {
    echo "✓ {$cabang->nama_cabang} ({$cabang->kode_cabang})\n";
}

echo "\n=== Selesai ===\n";
