<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (file_exists(APPPATH . 'config/routes_neural.php')) {
    $existing_routes = $route ?? [];
    $route = [];
    include(APPPATH . 'config/routes_neural.php');
    $neural_routes = $route;
    $route = $neural_routes + $existing_routes;
}
