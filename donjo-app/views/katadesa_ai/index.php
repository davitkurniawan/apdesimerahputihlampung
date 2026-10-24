<?php 
/**
 * Robust Bridge file to render Laravel Blade view from CI3 Controller
 */
try {
    // Ensure data is passed correctly
    $viewData = isset($data) ? $data : get_defined_vars();
    echo view('admin.katadesa_ai.index', $viewData)->render(); 
} catch (\Throwable $e) {
    echo "<div style='color: white; padding: 20px; background: #900;'>";
    echo "<h3>XOSID Neural Bridge Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre style='font-size: 10px; color: #ccc;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
?>
