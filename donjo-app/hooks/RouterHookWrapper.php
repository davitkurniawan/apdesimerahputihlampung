<?php

if (file_exists(__DIR__ . '/RouterHook.php')) {
    require_once __DIR__ . '/RouterHook.php';
}

class RouterHookWrapper extends RouterHook
{
    private function populateRoute()
    {
        try {
            $ci = &get_instance();
            $app = \Illuminate\Container\Container::getInstance();
            if ($app) {
                $route = $app->make('request')->route();
                if ($route) {
                    if ($ci && empty($ci->route)) {
                        $ci->route = $route;
                    }
                    if (property_exists($this, 'route') && empty($this->route)) {
                        $this->route = $route;
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    public function handlePreSystem(): void
    {
        $this->populateRoute();
        parent::handlePreSystem();
    }

    public function handlePostControllerConstructor(): void
    {
        try {
            $ci = &get_instance();
            $route = $ci->route ?? null;
            if (!$route) {
                $app = \Illuminate\Container\Container::getInstance();
                if ($app) {
                    $route = $app->make('request')->route();
                }
            }

            @file_put_contents(base_path('storage/logs/xosid_route_debug.log'),
                "[" . date('Y-m-d H:i:s') . "] WRAPPER POST_CONSTRUCT: route=" . ($route ? (is_object($route) ? get_class($route) . " name=" . $route->getName() : var_export($route, true)) : 'null') . " ci=" . ($ci ? 'yes' : 'no') . "\n",
                FILE_APPEND);

            if ($route) {
                // Run the parent's private processWebRouteParameters using Reflection to set internal state
                $refProcess = new ReflectionMethod('RouterHook', 'processWebRouteParameters');
                $refProcess->setAccessible(true);
                $params = $refProcess->invoke($this, $route);

                // If parameters is null (e.g. clean route with no parameters), safely convert to empty array
                if ($params === null) {
                    $params = [];
                }

                // Call the private executeRouteAction method using Reflection with safe parameters array
                $refExecute = new ReflectionMethod('RouterHook', 'executeRouteAction');
                $refExecute->setAccessible(true);
                $refExecute->invoke($this, $params);
                return;
            } else {
                // If there is no Laravel route, do not run the parent hook to avoid null type errors.
                return;
            }
        } catch (\Throwable $e) {
            @file_put_contents(base_path('storage/logs/xosid_route_debug.log'),
                "[" . date('Y-m-d H:i:s') . "] HOOK OVERRIDE FAILED: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND);
        }
    }

    public function handlePostController(): void
    {
        $this->populateRoute();
        parent::handlePostController();
    }
}
