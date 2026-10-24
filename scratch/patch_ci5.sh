#!/bin/bash
sed -i.bak '/\$CI = new \$class();/a\
if (isset($_GET["reflect5"])) {\
    require_once APPPATH . "controllers/Bumindes_penduduk_induk.php";\
    echo json_encode(get_class_methods("Bumindes_penduduk_induk"));\
    exit;\
}
' vendor/codeigniter/framework/system/core/CodeIgniter.php
