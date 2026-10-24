<?php
// scratch/dump_ci_auth.php

define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/donjo-app/helpers/illuminate_helper.php';

// Check if ci_auth is defined
if (function_exists('ci_auth')) {
    echo "ci_auth() function exists.\n";
    $refFunc = new ReflectionFunction('ci_auth');
    echo "Defined in: " . $refFunc->getFileName() . " on line " . $refFunc->getStartLine() . "\n";
    
    // Start session and run it
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user'] = 1;
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['id_grup'] = 1;
    
    $auth = ci_auth();
    echo "Returned type: " . gettype($auth) . "\n";
    if (is_object($auth)) {
        echo "Returned class: " . get_class($auth) . "\n";
        $refClass = new ReflectionClass($auth);
        echo "Methods:\n";
        foreach ($refClass->getMethods() as $method) {
            echo "  " . $method->getName() . "\n";
        }
        
        try {
            $user = $auth->user();
            echo "auth->user() returned: " . gettype($user) . "\n";
            if (is_object($user)) {
                echo "auth->user() class: " . get_class($user) . "\n";
                print_r($user);
            } else {
                var_dump($user);
            }
        } catch (\Throwable $e) {
            echo "auth->user() error: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "ci_auth() function does NOT exist.\n";
}
