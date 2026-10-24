#!/bin/bash
sed -i.bak '/function _execute($sql)/a\
file_put_contents(FCPATH."scratch/ci_queries.log", date("Y-m-d H:i:s")." - ".$sql."\\n", FILE_APPEND);
' vendor/codeigniter/framework/system/database/drivers/mysqli/mysqli_driver.php
