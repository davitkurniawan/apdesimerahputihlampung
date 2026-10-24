#!/bin/bash
sed -i.bak '/\$config\['\''index_page'\''\] = '\''index.php'\'';/a\
if (isset($_GET["dump_routes_shutdown"])) {\
    register_shutdown_function(function() {\
        $CI =& get_instance();\
        echo "ROUTES_DUMP:\\n";\
        print_r($CI->router->routes);\
    });\
}
' donjo-app/config/config.php
