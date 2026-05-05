<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== DATABASE SCHEMA FOR BARANG_MASUK ===\n\n";

$columns = Schema::getColumns('barang_masuk');

echo "Column Information:\n";
echo "==================\n\n";

foreach ($columns as $column) {
    echo "Column: {$column['name']}\n";
    echo "  Type: {$column['type']}\n";
    echo "  Nullable: " . ($column['nullable'] ? 'YES' : 'NO') . "\n";
    echo "  Default: " . ($column['default'] ?? 'none') . "\n\n";
}

// Also check via raw SQL
echo "\n=== RAW TABLE STRUCTURE ===\n\n";
$result = DB::select("DESCRIBE barang_masuk");
foreach ($result as $field) {
    echo "{$field->Field} | Type: {$field->Type} | Null: {$field->Null} | Key: {$field->Key} | Default: {$field->Default}\n";
}

?>
