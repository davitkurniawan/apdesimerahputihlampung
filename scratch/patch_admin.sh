#!/bin/bash
sed -i.bak '/parent::__construct();/a\
if (isset($_GET["dump_methods"])) { echo "METHODS_DUMP: " . implode(",", get_class_methods($this)); exit; }
' donjo-app/core/Admin_Controller.php
