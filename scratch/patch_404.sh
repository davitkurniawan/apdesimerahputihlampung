#!/bin/bash
sed -i.bak '/<h2 class="headline text-yellow">404<\/h2>/a\
<?php\
    $trace = debug_backtrace();\
    $log = "";\
    foreach($trace as $t) {\
        $file = isset($t["file"]) ? $t["file"] : "unknown";\
        $line = isset($t["line"]) ? $t["line"] : 0;\
        $func = isset($t["function"]) ? $t["function"] : "unknown";\
        $log .= "$file:$line - $func\\n";\
    }\
    file_put_contents(FCPATH."scratch/404_backtrace.log", $log);\
?>
' donjo-app/views/errors/html/error_404.php
