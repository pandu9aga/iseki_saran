<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suggestion;
use App\Services\SuggestionPdfService;

class AutoScoreOctober2026Suggestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suggestion:auto-score-october-2026 
                            {--user-id=1 : ID penilai / user leader (default: 1)}
                            {--skip-pdf : Hanya update database, lewati proses generate PDF}
                            {--dry-run : Jalankan simulasi tanpa menyimpan perubahan ke database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis menilai suggestions bulan 10 2026 untuk member 32, 135, 63 dengan skor B (kreatifitas: 3, ide: 0, usaha: 0), komentar, no penerimaan awal, dan generate PDF langsung';

    /**
     * Execute the console command.
     */
    public function handle(SuggestionPdfService $pdfService): int
    {
        $targetMembers = [32, 135, 63];
        $isDryRun = $this->option('dry-run');

        $this->info("=== Auto Score Suggestions (Bulan 10 2026) ===");
        $this->info("Target Member ID: " . implode(', ', $targetMembers));
        if ($isDryRun) {
            $this->warn("[DRY-RUN MODE AKTIF] Perubahan tidak akan disimpan ke database.");
        }

        // Query suggestions berdasarkan kriteria
        $suggestions = Suggestion::with(['member', 'user'])
            ->whereIn('Id_Member', $targetMembers)
            ->whereRaw("DATE_FORMAT(Date_First_Suggestion, '%Y-%m') = '2026-10'")
            ->orderBy('Id_Suggestion', 'asc')
            ->get();

        $totalFound = $suggestions->count();
        $this->info("Ditemukan {$totalFound} saran yang memenuhi kriteria.");

        if ($totalFound === 0) {
            $this->warn("Tidak ada saran yang ditemukan untuk diproses.");
            return self::SUCCESS;
        }

        $scoreBPayload = [
            'kreatifitas' => 3,
            'ide' => 0,
            'usaha' => 0,
        ];
        $comment = 'lingkungan kerja tambah nyaman';

        // Hitung nilai Acceptance_First_Suggestion tertinggi saat ini
        $currentMaxAcceptance = (int) (Suggestion::max('Acceptance_First_Suggestion') ?? 0);
        $this->info("Nomor penerimaan awal (Acceptance_First_Suggestion) saat ini tertinggi: {$currentMaxAcceptance}");

        $userId = (int) $this->option('user-id');
        $this->info("ID Penilai (Id_User): {$userId}");

        $updatedSuggestions = [];

        $this->line("\n=== FASE 1: UPDATE DATABASE ===");
        foreach ($suggestions as $index => $suggestion) {
            $this->line("--------------------------------------------------");
            $this->info(sprintf(
                "[%d/%d] Suggestion ID: %s | Member ID: %s (%s) | Tanggal: %s",
                $index + 1,
                $totalFound,
                $suggestion->Id_Suggestion,
                $suggestion->Id_Member,
                $suggestion->member->nama ?? 'Unknown',
                $suggestion->Date_First_Suggestion
            ));

            // Cek / assign Acceptance_First_Suggestion
            if (!$suggestion->Acceptance_First_Suggestion) {
                $currentMaxAcceptance++;
                $assignedAcceptance = $currentMaxAcceptance;
                $this->line("  -> Menetapkan Acceptance_First_Suggestion baru: {$assignedAcceptance}");
            } else {
                $assignedAcceptance = $suggestion->Acceptance_First_Suggestion;
                $this->line("  -> Menggunakan Acceptance_First_Suggestion yang sudah ada: {$assignedAcceptance}");
            }

            if ($isDryRun) {
                $this->line("  [Dry Run] Skor B: " . json_encode($scoreBPayload));
                $this->line("  [Dry Run] Komentar: {$comment}");
                $this->line("  [Dry Run] Id_User: {$userId}");
                continue;
            }

            // Update nilai saran ke database
            $suggestion->Score_B_Suggestion = json_encode($scoreBPayload);
            $suggestion->Comment_Suggestion = $comment;
            $suggestion->Acceptance_First_Suggestion = $assignedAcceptance;
            $suggestion->Id_User = $userId;
            $suggestion->save();

            $updatedSuggestions[] = $suggestion;
            $this->info("  ✓ Berhasil dinilai dan disimpan ke database (Id_User: {$userId}).");
        }

        if ($isDryRun) {
            $this->line("\n==================================================");
            $this->info("[Dry Run Selesai] Total saran dicek: {$totalFound}");
            return self::SUCCESS;
        }

        if ($this->option('skip-pdf')) {
            $this->line("\n==================================================");
            $this->warn("[--skip-pdf aktif] Proses generate PDF dilewati.");
            $this->info("Total database diperbarui : " . count($updatedSuggestions) . " saran");
            return self::SUCCESS;
        }

        $this->line("\n=== FASE 2: GENERATE PDF BELAKANGAN ===");
        $pdfSuccessCount = 0;
        $totalToGenerate = count($updatedSuggestions);

        foreach ($updatedSuggestions as $index => $suggestion) {
            $this->line("--------------------------------------------------");
            $this->info(sprintf(
                "[%d/%d] Generating PDF untuk Suggestion ID: %s (No. Penerimaan: %s)",
                $index + 1,
                $totalToGenerate,
                $suggestion->Id_Suggestion,
                $suggestion->Acceptance_First_Suggestion
            ));

            try {
                $pdfPath = $pdfService->generate((int) $suggestion->Id_Suggestion);
                $this->info("  ✓ PDF berhasil dibuat: " . basename($pdfPath));
                $pdfSuccessCount++;
            } catch (\Exception $e) {
                $this->error("  ✗ Gagal generate PDF: " . $e->getMessage());
            }
        }

        $this->line("\n==================================================");
        $this->info("Selesai Seluruh Proses!");
        $this->info("Total database diperbarui : " . count($updatedSuggestions) . " saran");
        $this->info("Total PDF berhasil dibuat : {$pdfSuccessCount} dari {$totalToGenerate}");

        return self::SUCCESS;
    }
}
