<?php
namespace App\Traits {
    trait Download {}
}

namespace {
    define('BASEPATH', 'test');
    class CI_Controller {}
    class Admin_Controller extends CI_Controller {}
    require_once '../donjo-app/controllers/Database.php';

    $ref = new ReflectionClass('Database');
    foreach($ref->getMethods() as $method) {
        echo $method->getName() . "\n";
    }
}
