<?php

// Membuat folder bootstrap cache otomatis di temporary folder Vercel agar tidak error 500
$cacheDir = '/tmp/storage/bootstrap/cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}
putenv("APP_STORAGE=/tmp/storage");

require __DIR__ . '/../public/index.php';