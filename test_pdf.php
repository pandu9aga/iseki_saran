<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$spreadsheet = IOFactory::load(__DIR__ . '/storage/app/templates/saran_perbaikan.xlsx');
$sheet = $spreadsheet->getActiveSheet();

$sheet->getPageSetup()
    ->setPaperSize(PageSetup::PAPERSIZE_A4)
    ->setFitToWidth(1)
    ->setFitToHeight(1);

$sheet->getPageMargins()
    ->setTop(0.2)
    ->setBottom(0.2)
    ->setLeft(0.2)
    ->setRight(0.2);

$writer = new Mpdf($spreadsheet);
$writer->save(__DIR__ . '/test_mpdf.pdf');

echo "Done.\n";
