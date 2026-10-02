<?php
/**
 * generate_pdf_worker.php
 *
 * Script PHP standalone yang di-trigger via HTTP secara fire-and-forget.
 * Memanfaatkan Apache yang bisa mengakses dirinya sendiri tanpa masalah permission.
 *
 * Dipanggil oleh dispatchBackground() via socket/curl ke:
 * http://127.0.0.1/iseki_saran/public/generate_pdf_worker.php
 *
 * Keamanan: validasi APP_KEY sebagai secret token.
 */

// Aktifkan processing setelah koneksi browser ditutup
ignore_user_abort(true);
set_time_limit(300);

// Pastikan file ini tidak bisa diakses publik tanpa secret
$secret   = $_POST['_secret'] ?? '';
$id       = (int) ($_POST['id'] ?? 0);

if (!$id || !$secret) {
    http_response_code(400);
    exit('Bad Request');
}

// Bootstrap Laravel
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Validasi secret dengan APP_KEY
$expected = config('app.key');
if (!hash_equals($expected, $secret)) {
    http_response_code(403);
    exit('Forbidden');
}

// Tutup koneksi ke browser/caller segera
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
} else {
    // Untuk Apache mod_php: flush buffer dan tutup koneksi
    header('Content-Length: 2');
    header('Connection: close');
    echo 'ok';
    flush();
}

// Generate PDF di background (setelah koneksi ditutup)
try {
    $pdfService = $app->make(\App\Services\SuggestionPdfService::class);
    $pdfService->generate($id);
    \Illuminate\Support\Facades\Log::info("generate_pdf_worker: PDF sukses untuk ID {$id}");
} catch (\Exception $e) {
    \Illuminate\Support\Facades\Log::error("generate_pdf_worker: Gagal ID {$id}: " . $e->getMessage());
}
