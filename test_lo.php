<?php
$librePath = 'C:\\xampp\\htdocs\\iseki_saran\\storage\\app\\LibreOfficePortable\\LibreOfficePortable.exe';
$dummy = 'C:\\xampp\\htdocs\\iseki_saran\\storage\\app\\tmp_test.xlsx';
copy('C:\\xampp\\htdocs\\iseki_saran\\storage\\app\\templates\\saran_perbaikan.xlsx', $dummy);

$cmd = sprintf(
    '"%s" --headless --convert-to pdf "%s" --outdir "%s"',
    $librePath,
    $dummy,
    dirname($dummy)
);
echo "Running:\n$cmd\n";
exec($cmd, $output, $resultCode);

echo "Exit Code: $resultCode\n";
echo "Output:\n" . implode("\n", $output) . "\n";
