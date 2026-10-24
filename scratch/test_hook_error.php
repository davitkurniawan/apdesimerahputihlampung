<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../donjo-app/hooks/RouterHookWrapper.php';

class MockRoute {
    public $is404 = false;
    public function parameters() {
        return [];
    }
}

class MockLoad {
    public function helper() {}
    public function library() {}
    public function model() {}
}

class MockCI {
    public $uri;
    public $router;
    public $route;
    public $load;
    public function __construct() {
        $this->uri = new stdClass();
        $this->router = new stdClass();
        $this->route = new MockRoute();
        $this->load = new MockLoad();
    }
}
if (!function_exists('ci')) {
    function ci() {
        return new MockCI();
    }
}

$hook = new RouterHookWrapper();
try {
    $hook->handlePostControllerConstructor([]);
    echo "Success!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString() . "\n";
}
