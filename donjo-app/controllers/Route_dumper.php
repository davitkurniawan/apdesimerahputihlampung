<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Route_dumper extends CI_Controller {
    public function index() {
        echo "<pre>";
        print_r($this->router->routes);
        echo "</pre>";
    }
}
