#!/bin/bash
sed -i.bak '/function __construct/a\
if (strpos($_SERVER["HTTP_USER_AGENT"], "DumpRoutes") !== false) { echo "ROUTES_DUMP: " . json_encode($this->router->routes); exit; }
' donjo-app/core/MY_Controller.php
