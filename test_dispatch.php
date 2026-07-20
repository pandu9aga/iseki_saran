<?php
$phpBinary = PHP_BINARY;
$testScript = __DIR__ . '/test_bg.php';
$cmd = 'start /B cmd /c ""' . $phpBinary . '" "' . $testScript . '" > NUL 2>&1"';
pclose(popen($cmd, 'r'));
echo "Dispatched.\n";
