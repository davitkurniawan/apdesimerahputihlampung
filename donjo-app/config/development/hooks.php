<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['pre_controller'][] = function() {
    global $class, $method, $params;

    $neural_controllers = [
        'AuthenticatedSessionController',
        'RegisteredUserController',
        'NewPasswordController',
        'PasswordResetLinkController'
    ];

    if (in_array($class, $neural_controllers)) {
        $subFolder = (strpos($_SERVER['REQUEST_URI'] ?? '', 'layanan-mandiri') !== false) ? 'fmandiri' : 'auth';
        $controller_file = APPPATH . "controllers/{$subFolder}/{$class}.php";

        if (file_exists($controller_file)) {
            require_once $controller_file;
            if (class_exists($class)) {
                $controller = new $class();
                try {
                    $res = call_user_func_array([$controller, $method], $params ?? []);
                    if ($res instanceof \Illuminate\View\View) {
                        echo $res->render();
                    } elseif ($res instanceof \Symfony\Component\HttpFoundation\Response) {
                        $res->send();
                    }
                    exit;
                } catch (\Throwable $hookErr) {
                    log_message('error', 'Neural controller hook error: ' . $hookErr->getMessage());
                }
            }
        }
    }
};
