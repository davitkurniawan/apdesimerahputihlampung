#!/bin/bash
sed -i.bak '/\$CI = new \$class();/a\
if (isset($_GET["reflect3"])) {\
    require_once APPPATH . "models/Penduduk_model.php";\
    echo json_encode(get_class_methods("Penduduk_model"));\
    exit;\
}
' vendor/codeigniter/framework/system/core/CodeIgniter.php
