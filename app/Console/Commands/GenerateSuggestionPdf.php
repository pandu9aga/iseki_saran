<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SuggestionPdfService;

class GenerateSuggestionPdf extends Command
{
    /**
     * Nama command artisan.
     * Contoh penggunaan: php artisan suggestion:generate-pdf 42
     */
    protected $signature = 'suggestion:generate-pdf {id : ID suggestion yang akan di-generate PDF-nya}';

    protected $description = 'Generate PDF untuk satu saran berdasarkan ID (bisa dijalankan manual untuk re-generate)';

    public function handle(SuggestionPdfService $pdfService): int
    {
        $id = (int) $this->argument('id');

        $this->info("Memulai generate PDF untuk Suggestion ID: {$id}");

        try {
            $pdfService->generate($id);
            $this->info("✓ PDF berhasil dibuat untuk Suggestion ID: {$id}");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("✗ Gagal generate PDF untuk Suggestion ID: {$id}");
            $this->error("  Pesan: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
