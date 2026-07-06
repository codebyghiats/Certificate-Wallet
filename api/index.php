<?php

// 1. PAKSA PHP UNTUK MENAMPILKAN ERROR KE BROWSER
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 2. Definisikan folder temporary untuk storage Laravel di Vercel
$privateStorage = '/tmp/storage';

// 3. Buat folder bootstrap cache & views otomatis jika belum ada
if (!is_dir($privateStorage . '/bootstrap/cache')) {
    mkdir($privateStorage . '/bootstrap/cache', 0755, true);
}
if (!is_dir($privateStorage . '/framework/views')) {
    mkdir($privateStorage . '/framework/views', 0755, true);
}

// 4. Set Environment khusus agar Laravel tahu foldernya dipindah ke /tmp
putenv("APP_STORAGE={$privateStorage}");
putenv("VIEW_COMPILED_PATH={$privateStorage}/framework/views");

// 5. Panggil file index utama Laravel
require __DIR__ . '/../public/index.php';