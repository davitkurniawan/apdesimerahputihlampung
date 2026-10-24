<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Define dummy functions
if (!function_exists('App\Models\usia')) { function usia($tgl) { return 0; } }

// Find all classes in app/Models
$dir = __DIR__ . '/../app/Models';
$files = scandir($dir);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $className = 'App\\Models\\' . pathinfo($file, PATHINFO_FILENAME);
        if (class_exists($className)) {
            try {
                $ref = new ReflectionClass($className);
                if ($ref->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) && !$ref->isAbstract()) {
                    $instance = new $className();
                    $table = $instance->getTable();
                    if ($table === 'tweb_penduduk') {
                        echo "Model: $className uses table tweb_penduduk\n";
                    }
                }
            } catch (\Throwable $e) {
                // Ignore errors
            }
        }
    }
}
