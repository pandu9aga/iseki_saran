<?php

namespace App\Jobs;

use App\Services\SuggestionPdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $suggestionId;

    public function __construct(int $suggestionId)
    {
        $this->suggestionId = $suggestionId;
    }

    public function handle(SuggestionPdfService $pdfService): void
    {
        try {
            $pdfService->generate($this->suggestionId);
            Log::info("GeneratePdfJob sukses untuk ID: {$this->suggestionId}");
        } catch (\Exception $e) {
            Log::error("GeneratePdfJob gagal untuk ID {$this->suggestionId}: " . $e->getMessage());
            throw $e;
        }
    }
}
