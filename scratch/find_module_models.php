<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Define dummy functions
if (!function_exists('App\Models\usia')) { function usia($tgl) { return 0; } }

// Recursively find all PHP files in Modules
function find_php_files($dir) {
    $files = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            $files = array_merge($files, find_php_files($path));
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $files[] = $path;
        }
    }
    return $files;
}

$module_dir = __DIR__ . '/../Modules';
if (is_dir($module_dir)) {
    $files = find_php_files($module_dir);
    foreach ($files as $file) {
        // Read file contents to look for namespaces and class names
        $content = file_get_contents($file);
        if (preg_match('/namespace\s+([^;]+);/i', $content, $ns_match)) {
            if (preg_match('/class\s+(\w+)/i', $content, $class_match)) {
                $className = trim($ns_match[1]) . '\\' . trim($class_match[1]);
                if (class_exists($className)) {
                    try {
                        $ref = new ReflectionClass($className);
                        if ($ref->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) && !$ref->isAbstract()) {
                            $instance = new $className();
                            $table = $instance->getTable();
                            echo "Module Model: $className uses table: $table\n";
                        }
                    } catch (\Throwable $e) {}
                }
            }
        }
    }
}
