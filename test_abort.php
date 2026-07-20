<?php
ignore_user_abort(true);
set_time_limit(0);
file_put_contents(__DIR__ . '/test_abort.log', "Started\n");
sleep(10);
file_put_contents(__DIR__ . '/test_abort.log', "Finished\n", FILE_APPEND);
echo "Done";
