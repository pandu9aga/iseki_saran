<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suggestion;
use App\Services\SuggestionPdfService;

class GenerateMissingPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iseki:generate-missing-pdfs {--month= : Bulan format YYYY-MM}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PDF untuk saran yang berstatus selesai namun PDF-nya belum ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month');
        $query = Suggestion::whereNotNull('Acceptance_First_Suggestion')
            ->whereNotNull('Date_First_Suggestion');
            // ->where('Status_Suggestion', 1); // Opsional: hanya yang sudah dinilai

        if ($month) {
            $query->whereRaw("DATE_FORMAT(Date_First_Suggestion, '%Y-%m') = ?", [$month]);
        }

        $suggestions = $query->get();
        $missingCount = 0;
        $generatedCount = 0;

        $pdfService = app(SuggestionPdfService::class);

        foreach ($suggestions as $s) {
            $bulan = date('Y-m', strtotime($s->Date_First_Suggestion));
            $acc = str_pad($s->Acceptance_First_Suggestion, 5, '0', STR_PAD_LEFT);
            $path = public_path("uploads/pdf/{$bulan}/Saran_Perbaikan_{$bulan}_{$acc}.pdf");

            if (!file_exists($path)) {
                $missingCount++;
                $this->info("PDF hilang untuk ID: {$s->Id_Suggestion} (No. Penerimaan: {$acc})");

                // Generate langsung (sinkron) — Mpdf writer murni PHP, tanpa worker/eksternal
                try {
                    $pdfService->generate($s->Id_Suggestion);
                    $generatedCount++;
                } catch (\Exception $e) {
                    $this->error("Gagal generate ID {$s->Id_Suggestion}: " . $e->getMessage());
                }
            }
        }

        $this->info("Total PDF yang hilang: {$missingCount}");
        $this->info("Berhasil dibuat: {$generatedCount} PDF.");

        return Command::SUCCESS;
    }
}
