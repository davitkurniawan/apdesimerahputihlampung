#!/bin/bash
sed -i.bak '/function __construct/a\
if (isset($_GET["dump_methods"])) { echo "METHODS_DUMP: " . implode(",", get_class_methods($this)); exit; }
' donjo-app/core/MY_Controller.php
