<?php
// qr_local.php - menggunakan phpqrcode (qrlib.php)
// Pastikan Anda sudah menaruh qrlib.php di folder yang sama.
// Dapatkan library: https://github.com/chillerlan/php-qrcode (atau repository 'phpqrcode').
// Banyak versi; contoh API di bawah sesuai library 'PHP QR Code' (QRcode::png).

require_once 'phpqrcode/qrlib.php'; // file library

$url = isset($_GET['url']) ? $_GET['url'] : 'https://example.com';
$size = isset($_GET['size']) ? intval($_GET['size']) : 3; // scale / module size
$margin = isset($_GET['margin']) ? intval($_GET['margin']) : 2; // border modules

// Jika ingin langsung meng-output gambar PNG:
header('Content-Type: image/png');
QRcode::png($url, null, QR_ECLEVEL_L, $size, $margin);
// kalau library Anda punya namespace/class berbeda, cek dokumentasinya
exit;
