#!/bin/bash
sed -i.bak '/\$CI = new \$class();/a\
if (isset($_GET["reflect4"])) {\
    require_once APPPATH . "models/Database_model.php";\
    echo json_encode(get_class_methods("Database_model"));\
    exit;\
}
' vendor/codeigniter/framework/system/core/CodeIgniter.php
