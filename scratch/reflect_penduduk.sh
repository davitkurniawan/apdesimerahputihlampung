#!/bin/bash
sed -i.bak '/function __construct/a\
if (isset($_GET["reflect"])) { var_dump(get_class_methods($this)); exit; }
' donjo-app/core/MY_Controller.php
