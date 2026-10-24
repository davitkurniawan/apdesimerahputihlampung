#!/bin/bash
sed -i.bak '/public function show_404($page = '\'''\''/a\
    $trace = debug_backtrace();\
    $log = "";\
    foreach($trace as $t) {\
        $file = isset($t["file"]) ? $t["file"] : "unknown";\
        $line = isset($t["line"]) ? $t["line"] : 0;\
        $func = isset($t["function"]) ? $t["function"] : "unknown";\
        $log .= "$file:$line - $func\\n";\
    }\
    file_put_contents(FCPATH."scratch/404_backtrace2.log", $log);
' vendor/codeigniter/framework/system/core/Exceptions.php
