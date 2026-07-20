<?php
file_put_contents(__DIR__ . '/test_bg.log', "Started at " . time() . "\n");
sleep(3);
file_put_contents(__DIR__ . '/test_bg.log', "Finished at " . time() . "\n", FILE_APPEND);
