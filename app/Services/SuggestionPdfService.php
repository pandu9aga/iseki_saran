<?php

namespace App\Services;

use App\Jobs\GeneratePdfJob;
use App\Models\Suggestion;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;

class SuggestionPdfService
{
    /**
     * Generate PDF untuk satu suggestion berdasarkan ID.
     * Render dilakukan via LibreOffice Portable.
     *
     * @param int $id
     * @return string path lengkap PDF yang berhasil dibuat
     * @throws \Exception
     */
    public function generate(int $id): string
    {
        $suggestion = Suggestion::with(['user', 'member'])->find($id);

        if (!$suggestion) {
            throw new \Exception("Suggestion ID {$id} tidak ditemukan.");
        }

        if (!$suggestion->Acceptance_First_Suggestion || !$suggestion->Date_First_Suggestion) {
            throw new \Exception("Suggestion ID {$id} belum memiliki nomor penerimaan atau tanggal.");
        }

        // ===============================
        // LOAD TEMPLATE EXCEL
        // ===============================
        $spreadsheet = IOFactory::load(
            storage_path('app/templates/saran_perbaikan.xlsx')
        );

        foreach ($spreadsheet->getDefinedNames() as $definedName) {
            $spreadsheet->removeDefinedName($definedName->getName());
        }

        $sheet = $spreadsheet->getActiveSheet();

        // ===============================
        // MAPPING CELL
        // ===============================
        $sheet->setCellValue('K4',  $suggestion->Date_First_Suggestion ?? '');
        $sheet->setCellValue('AD4', $suggestion->Date_Last_Suggestion ?? '');
        $sheet->setCellValue('C5',  $suggestion->member->nik ?? '');
        $sheet->setCellValue('Q5',  $suggestion->Team_Suggestion ?? '');
        $sheet->setCellValue('Y5',  $suggestion->member->nama ?? '');
        $sheet->setCellValue('Q11', $suggestion->Theme_Suggestion ?? '');
        $sheet->setCellValue('B16', $suggestion->Content_Suggestion ?? '');
        $sheet->setCellValue('AG16', $suggestion->Improvement_Suggestion ?? '');
        $sheet->setCellValue('AF38', $suggestion->Comment_Suggestion ?? '');
        $sheet->setCellValue('BC39', $suggestion->user->Name_User ?? '');

        // ===============================
        // LINGKARAN TEMA (pink)
        // ===============================
        $positions = [
            'keselamatan' => 'C8',
            'kualitas'    => 'E8',
            'cost'        => 'G8',
            'waktu'       => 'I8',
            'lingkungan'  => 'K8',
            'moral'       => 'M8',
            'fasilitas'   => 'W15',
            'mould jig'   => 'AA15',
            'set up'      => 'AG15',
            'material'    => 'AK15',
            'metode'      => 'AO15',
            'informasi'   => 'AS15',
        ];

        $theme = strtolower(trim($suggestion->Theme_Suggestion ?? ''));
        $targetCell = null;
        foreach ($positions as $keyword => $cell) {
            if (stripos($theme, $keyword) !== false) {
                $targetCell = $cell;
                break;
            }
        }

        if ($targetCell && function_exists('imagecreatetruecolor')) {
            $circleFile = $this->getCircleImagePath(255, 0, 151, 'theme');

            $drawing = new Drawing();
            $drawing->setName('ThemeCircle');
            $drawing->setPath($circleFile);
            $drawing->setCoordinates($targetCell);
            $specialCells = ['C8', 'E8', 'G8', 'I8', 'K8', 'M8'];
            if (in_array($targetCell, $specialCells)) {
                $drawing->setOffsetX(12);
                $drawing->setOffsetY(-3);
            } else {
                $drawing->setOffsetX(-3);
                $drawing->setOffsetY(0);
            }
            $drawing->setWidth(36);
            $drawing->setHeight(36);
            $drawing->setWorksheet($sheet);
        }

        // ===============================
        // LINGKARAN STATUS (oranye)
        // ===============================
        $statusCell = null;
        if ($suggestion->Status_Suggestion == 0) {
            $statusCell = 'AL5';
        } elseif ($suggestion->Status_Suggestion == 1) {
            $statusCell = 'AN5';
        }

        if ($statusCell && function_exists('imagecreatetruecolor')) {
            $circleFile = $this->getCircleImagePath(212, 109, 0, 'status');

            $drawing = new Drawing();
            $drawing->setName('StatusCircle');
            $drawing->setPath($circleFile);
            $drawing->setCoordinates($statusCell);
            $drawing->setOffsetX(-2);
            $drawing->setOffsetY(5);
            $drawing->setWidth(64);
            $drawing->setHeight(64);
            $drawing->setWorksheet($sheet);
        }

        // ===============================
        // LINGKARAN SCORE A (hitam)
        // ===============================
        $scoreMap = [
            0  => 'E37', 1  => 'F37', 2  => 'G37', 3  => 'H37',
            4  => 'I37', 5  => 'J37', 6  => 'K37', 7  => 'L37',
            8  => 'M37', 9  => 'O37', 10 => 'Q37', 11 => 'S37',
            12 => 'U37', 13 => 'W37', 14 => 'Y37', 15 => 'AA37',
        ];

        if (!is_null($suggestion->Score_A_Suggestion) && isset($scoreMap[$suggestion->Score_A_Suggestion])) {
            $scoreCell = $scoreMap[$suggestion->Score_A_Suggestion];

            if (function_exists('imagecreatetruecolor')) {
                $circleFile = $this->getCircleImagePath(0, 0, 0, 'scoreA');

                $drawing = new Drawing();
                $drawing->setName('ScoreCircle');
                $drawing->setPath($circleFile);
                $drawing->setCoordinates($scoreCell);
                $drawing->setOffsetX($suggestion->Score_A_Suggestion <= 7 ? 0 : 15);
                $drawing->setOffsetY(-2);
                $drawing->setWidth(32);
                $drawing->setHeight(32);
                $drawing->setWorksheet($sheet);
            }
        }

        // ===============================
        // LINGKARAN SCORE B (hitam)
        // ===============================
        if (!empty($suggestion->Score_B_Suggestion)) {
            $scoreB = json_decode($suggestion->Score_B_Suggestion, true);

            if (is_array($scoreB)) {
                $mappingB = [
                    'kreatifitas' => [0 => 'Y42', 1 => 'Z42', 2 => 'AA42', 3 => 'AB42', 4 => 'AC42', 5 => 'AD42'],
                    'ide'         => [0 => 'Y43', 1 => 'Z43', 2 => 'AA43', 3 => 'AB43', 4 => 'AC43', 5 => 'AD43'],
                    'usaha'       => [0 => 'Y44', 1 => 'Z44', 2 => 'AA44', 3 => 'AB44', 4 => 'AC44', 5 => 'AD44'],
                ];

                foreach ($mappingB as $key => $map) {
                    if (isset($scoreB[$key])) {
                        $val = (int) $scoreB[$key];
                        if (isset($map[$val]) && function_exists('imagecreatetruecolor')) {
                            $circleFile = $this->getCircleImagePath(0, 0, 0, 'scoreB');

                            $drawing = new Drawing();
                            $drawing->setName('ScoreB_' . $key);
                            $drawing->setPath($circleFile);
                            $drawing->setCoordinates($map[$val]);
                            $drawing->setOffsetX(0);
                            $drawing->setOffsetY(-2);
                            $drawing->setWidth(32);
                            $drawing->setHeight(32);
                            $drawing->setWorksheet($sheet);
                        }
                    }
                }

                $sheet->setCellValue('AA45', $suggestion->total_score);
            }
        }

        // ===============================
        // FOTO KONTEN & PERBAIKAN
        // ===============================
        $this->attachPhotos($sheet, $suggestion);

        // ===============================
        // NOMOR PENERIMAAN
        // ===============================
        if (!empty($suggestion->Acceptance_First_Suggestion)) {
            $teamPrefix = match (strtolower(trim($suggestion->Team_Suggestion ?? ''))) {
                'assembling' => 0,
                'painting'   => 1,
                'dst'        => 3,
                default      => 0,
            };

            $sheet->setCellValue('AR3', 6);
            $sheet->setCellValue('AT3', 2);
            $sheet->setCellValue('AV3', $teamPrefix);

            $accFirst = str_pad($suggestion->Acceptance_First_Suggestion, 5, '0', STR_PAD_LEFT);
            $sheet->setCellValue('AX3', substr($accFirst, 0, 1));
            $sheet->setCellValue('AZ3', substr($accFirst, 1, 1));
            $sheet->setCellValue('BB3', substr($accFirst, 2, 1));
            $sheet->setCellValue('BD3', substr($accFirst, 3, 1));
            $sheet->setCellValue('BF3', substr($accFirst, 4, 1));
        }

        // ===============================
        // PAGE SETUP PDF
        // ===============================
        $sheet->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(1);

        $sheet->getPageMargins()
            ->setTop(0.2)
            ->setBottom(0.2)
            ->setLeft(0.2)
            ->setRight(0.2);

        // ===============================
        // PATH OUTPUT PDF
        // ===============================
        $bulan = date('Y-m', strtotime($suggestion->Date_First_Suggestion));
        $dir   = public_path("uploads/pdf/{$bulan}");

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $acc  = str_pad($suggestion->Acceptance_First_Suggestion ?? 0, 5, '0', STR_PAD_LEFT);
        $path = "{$dir}/Saran_Perbaikan_{$bulan}_{$acc}.pdf";

        // === Render PDF via LibreOffice Portable ===
        $tempExcel = storage_path('app/tmp_excel_' . $suggestion->Id_Suggestion . '_' . time() . '.xlsx');

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempExcel);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        if (!file_exists($tempExcel) || filesize($tempExcel) === 0) {
            @unlink($tempExcel);
            throw new \Exception('Gagal menyimpan file Excel sementara.');
        }

        $libreOfficePath = storage_path('app/LibreOfficePortable/App/libreoffice/program/soffice.exe');
        $outdir = dirname($tempExcel);
        
        $command = "\"{$libreOfficePath}\" --headless --convert-to pdf \"{$tempExcel}\" --outdir \"{$outdir}\"";
        
        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);

        $tempPdf = str_replace('.xlsx', '.pdf', $tempExcel);

        if ($returnVar !== 0 || !file_exists($tempPdf) || filesize($tempPdf) === 0) {
            @unlink($tempExcel);
            @unlink($tempPdf);
            throw new \Exception("Gagal konversi PDF via LibreOffice. Return: {$returnVar}");
        }

        // Pindahkan ke path final (overwrite) secara aman di Windows
        if (file_exists($path)) {
            @unlink($path);
        }

        if (!@rename($tempPdf, $path)) {
            copy($tempPdf, $path);
            @unlink($tempPdf);
        }

        @unlink($tempExcel);

        Log::info("PDF berhasil dibuat via LibreOffice: {$path}");

        return $path;
    }

    /**
     * Dispatch konversi PDF ke queue (non-blocking).
     * Save akan langsung selesai, PDF dibuat oleh queue worker.
     *
     * @param int $id
     * @return bool true jika berhasil diantrekan ke queue
     */
    public function dispatchBackground(int $id): bool
    {
        try {
            GeneratePdfJob::dispatch($id);
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal dispatch GeneratePdfJob (ID ' . $id . '): ' . $e->getMessage());
            return false;
        }
    }

    // ===============================
    // PRIVATE HELPERS
    // ===============================

    /**
     * Dapatkan path asset lingkaran statis (dibuat & di-cache sekali, digunakan selamanya).
     */
    private function getCircleImagePath(int $r, int $g, int $b, string $type = 'circle'): string
    {
        $dir = storage_path('app/pdf_assets');
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $file = $dir . "/circle_{$type}_{$r}_{$g}_{$b}.png";
        if (file_exists($file) && filesize($file) > 0) {
            return $file;
        }

        $size      = 80;
        $thickness = 10;
        $img       = imagecreatetruecolor($size, $size);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        $color = imagecolorallocate($img, $r, $g, $b);
        imagesetthickness($img, $thickness);
        $margin = $thickness + 6;
        imageellipse($img, $size / 2, $size / 2, $size - $margin, $size - $margin, $color);

        imagepng($img, $file);
        imagedestroy($img);

        return $file;
    }

    /**
     * Optimasi ukuran foto untuk PDF:
     * - Downscale foto resolusi tinggi ke dimensi ideal cetak (max 900x650 px).
     * - Menjaga orientasi EXIF (kamera smartphone).
     * - Cache hasil optimasi sehingga proses berikutnya instan (0.001 detik).
     */
    private function getOptimizedPhotoPath(string $sourcePath): string
    {
        if (!file_exists($sourcePath)) {
            return $sourcePath;
        }

        $info = @getimagesize($sourcePath);
        if (!$info) {
            return $sourcePath;
        }

        [$origW, $origH, $type] = $info;

        // Jika resolusi dan file sudah kecil, gunakan langsung
        $maxW = 900;
        $maxH = 650;
        if ($origW <= $maxW && $origH <= $maxH && filesize($sourcePath) <= 250 * 1024) {
            return $sourcePath;
        }

        $cacheDir = storage_path('app/cache_pdf_photos');
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0777, true);
        }
        $cacheFile = $cacheDir . '/' . md5($sourcePath . '_' . filemtime($sourcePath)) . '.jpg';
        if (file_exists($cacheFile) && filesize($cacheFile) > 0) {
            return $cacheFile;
        }

        $ratio = min($maxW / $origW, $maxH / $origH, 1.0);
        $newW = (int) round($origW * $ratio);
        $newH = (int) round($origH * $ratio);

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            default        => null,
        };

        if (!$src) {
            return $sourcePath;
        }

        // Koreksi orientasi EXIF jika ada (foto kamera HP)
        if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($sourcePath);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $src = imagerotate($src, 180, 0);
                        break;
                    case 6:
                        $src = imagerotate($src, -90, 0);
                        [$newW, $newH] = [$newH, $newW];
                        break;
                    case 8:
                        $src = imagerotate($src, 90, 0);
                        [$newW, $newH] = [$newH, $newW];
                        break;
                }
            }
        }

        $dst = imagecreatetruecolor($newW, $newH);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $white);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, imagesx($src), imagesy($src));
        imagedestroy($src);

        imagejpeg($dst, $cacheFile, 82);
        imagedestroy($dst);

        return $cacheFile;
    }

    private function attachPhotos($sheet, $suggestion): void
    {
        // Content Photos
        if (!empty($suggestion->Content_Photos_Suggestion)) {
            $photos = json_decode($suggestion->Content_Photos_Suggestion, true);
            if (is_array($photos)) {
                foreach ($photos as $i => $photoName) {
                    $filePath = public_path('uploads/contents/' . $photoName);
                    $cell     = $i == 0 ? 'B19' : ($i == 1 ? 'B24' : null);
                    if ($cell && !empty($photoName) && file_exists($filePath)) {
                        $optPath = $this->getOptimizedPhotoPath($filePath);
                        $drawing = new Drawing();
                        $drawing->setName('ContentPhoto' . ($i + 1));
                        $drawing->setPath($optPath);
                        $drawing->setCoordinates($cell);
                        $drawing->setOffsetX(200);
                        $drawing->setOffsetY(20);
                        $drawing->setWidthAndHeight(660, 420);
                        $drawing->setWorksheet($sheet);
                    }
                }
            }
        }

        // Improvement Photos
        if (!empty($suggestion->Improvement_Photos_Suggestion)) {
            $photos = json_decode($suggestion->Improvement_Photos_Suggestion, true);
            if (is_array($photos)) {
                foreach ($photos as $i => $photoName) {
                    $filePath = public_path('uploads/improvements/' . $photoName);
                    $cell     = $i == 0 ? 'AG19' : ($i == 1 ? 'AG24' : null);
                    if ($cell && !empty($photoName) && file_exists($filePath)) {
                        $optPath = $this->getOptimizedPhotoPath($filePath);
                        $drawing = new Drawing();
                        $drawing->setName('ImprovementPhoto' . ($i + 1));
                        $drawing->setPath($optPath);
                        $drawing->setCoordinates($cell);
                        $drawing->setOffsetX(200);
                        $drawing->setOffsetY(20);
                        $drawing->setWidthAndHeight(660, 420);
                        $drawing->setWorksheet($sheet);
                    }
                }
            }
        }
    }

    private function deleteDir(string $dirPath): void
    {
        if (!is_dir($dirPath)) return;
        $files = array_diff(scandir($dirPath), ['.', '..']);
        foreach ($files as $file) {
            $full = "$dirPath/$file";
            is_dir($full) ? $this->deleteDir($full) : @unlink($full);
        }
        @rmdir($dirPath);
    }
}
