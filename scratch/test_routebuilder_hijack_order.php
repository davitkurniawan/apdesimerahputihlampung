<?php
define('FCPATH', __DIR__ . '/../');
define('APPPATH', __DIR__ . '/../donjo-app/');

// 1. Register our class loader hook (Simulates xosid_emergency_autoload.php)
spl_autoload_register(function ($class) {
    if ($class === 'OpenSID\RouteBuilder') {
        $file = FCPATH . 'vendor/opensid/router/src/RouteBuilder.php';
        if (file_exists($file)) {
            require_once $file;
            
            // Register custom routes
            $controllers = ["pengurus"];
            foreach ($controllers as $ctrl) {
                \OpenSID\RouteBuilder::any($ctrl . '/datatables', 'buku_umum/' . $ctrl . '/datatables');
            }
            echo "Successfully hijacked RouteBuilder and registered routes!\n";
            return true;
        }
    }
});

// 2. Load vendor/autoload.php
require_once FCPATH . 'vendor/autoload.php';

// 3. Now trigger RouteBuilder loading
if (class_exists('OpenSID\RouteBuilder')) {
    echo "RouteBuilder exists!\n";
    \OpenSID\RouteBuilder::compileAll();
    $routes = \OpenSID\RouteBuilder::getRoutes();
    echo "Compiled routes:\n";
    print_r($routes);
} else {
    echo "Failed to load RouteBuilder\n";
}
