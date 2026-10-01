<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ImageResizeService
{
    /**
     * Resize dan simpan foto ke direktori tujuan (public path).
     *
     * @param string|UploadedFile $source File path, instance UploadedFile, atau data biner (string decoded)
     * @param string $destinationDir Direktori tujuan (misal: public_path('uploads/contents'))
     * @param string|null $filename Nama file yang diinginkan (opsional)
     * @param int $maxDimension Dimensi maksimal lebar/tinggi (default 1000px)
     * @param int $quality Kualitas JPEG (default 82%)
     * @return string Nama file yang berhasil disimpan
     */
    public static function resizeAndSave($source, string $destinationDir, ?string $filename = null, int $maxDimension = 1000, int $quality = 82): string
    {
        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0777, true);
        }

        if (!$filename) {
            $filename = time() . '_' . uniqid() . '.jpg';
        } else {
            // Selalu gunakan ekstensi .jpg untuk hasil resize JPEG yang konsisten
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($ext !== 'jpg' && $ext !== 'jpeg') {
                $filename = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
            }
        }

        $destPath = rtrim($destinationDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        // Sumber: UploadedFile
        if ($source instanceof UploadedFile) {
            $sourcePath = $source->getRealPath();
            self::processFile($sourcePath, $destPath, $maxDimension, $quality);
            return $filename;
        }

        // Sumber: Path file fisik yang ada di disk
        if (is_string($source) && @file_exists($source)) {
            self::processFile($source, $destPath, $maxDimension, $quality);
            return $filename;
        }

        // Sumber: Data biner mentah (hasil base64_decode)
        if (is_string($source)) {
            self::processBinary($source, $destPath, $maxDimension, $quality);
            return $filename;
        }

        return $filename;
    }

    /**
     * Proses file fisik.
     */
    private static function processFile(string $sourcePath, string $destPath, int $maxDim, int $quality): void
    {
        $info = @getimagesize($sourcePath);
        if (!$info) {
            @copy($sourcePath, $destPath);
            return;
        }

        [$origW, $origH, $type] = $info;

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            default        => null,
        };

        if (!$src) {
            @copy($sourcePath, $destPath);
            return;
        }

        // Koreksi orientasi EXIF (kamera smartphone)
        if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($sourcePath);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $src = imagerotate($src, 180, 0);
                        break;
                    case 6:
                        $src = imagerotate($src, -90, 0);
                        [$origW, $origH] = [$origH, $origW];
                        break;
                    case 8:
                        $src = imagerotate($src, 90, 0);
                        [$origW, $origH] = [$origH, $origW];
                        break;
                }
            }
        }

        self::renderAndSave($src, $origW, $origH, $destPath, $maxDim, $quality);
    }

    /**
     * Proses data biner (base64 string decoded).
     */
    private static function processBinary(string $binaryData, string $destPath, int $maxDim, int $quality): void
    {
        $src = @imagecreatefromstring($binaryData);
        if (!$src) {
            @file_put_contents($destPath, $binaryData);
            return;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        self::renderAndSave($src, $origW, $origH, $destPath, $maxDim, $quality);
    }

    /**
     * Downscale dan simpan ke JPEG.
     */
    private static function renderAndSave($src, int $origW, int $origH, string $destPath, int $maxDim, int $quality): void
    {
        $ratio = min($maxDim / max($origW, 1), $maxDim / max($origH, 1), 1.0);
        $newW = (int) round($origW * $ratio);
        $newH = (int) round($origH * $ratio);

        $dst = imagecreatetruecolor($newW, $newH);

        // Latar belakang putih untuk menangani transparansi PNG
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $white);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        imagejpeg($dst, $destPath, $quality);
        imagedestroy($dst);
    }
}
