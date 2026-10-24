#!/bin/bash
sed -i.bak '/\$CI = new \$class();/a\
if (isset($_GET["reflect2"])) {\
    require_once APPPATH . "controllers/Penduduk.php";\
    echo json_encode(get_class_methods("MY_Controller"));\
    exit;\
}
' vendor/codeigniter/framework/system/core/CodeIgniter.php
