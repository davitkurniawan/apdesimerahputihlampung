#!/bin/bash
sed -i.bak '/require_once BASEPATH . '\''core\/CodeIgniter.php'\'';/a\
if (isset($_GET["dump_routes"])) { $CI =& get_instance(); echo json_encode($CI->router->routes); exit; }
' index.php
