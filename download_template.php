<?php
/**
 * XOSID - Neural Core Template Downloader
 * Direct template serving bypass to avoid ionCube routing & middleware conflicts
 */

// 1. Direct path to the template file
$templateFile = __DIR__ . '/storage/app/template/impor/format-impor-excel.xlsm';

// 2. Fallback to storage helper path or current directory relative paths just in case
if (!file_exists($templateFile)) {
    // Try sub-folder or relative paths
    $templateFile = dirname(__DIR__) . '/storage/app/template/impor/format-impor-excel.xlsm';
}

if (file_exists($templateFile) && is_readable($templateFile)) {
    // 3. Clear any active output buffers to prevent file corruption
    if (ob_get_level()) {
        ob_end_clean();
    }

    // 4. Set robust streaming headers for Excel Macro-Enabled Workbook (.xlsm)
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
    header('Content-Disposition: attachment; filename="format-impor-excel.xlsm"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($templateFile));

    // 5. Stream file content and terminate
    readfile($templateFile);
    exit;
} else {
    // 6. Handle error gracefully
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    echo "<p>Template file format-impor-excel.xlsm could not be found or is not readable on the server.</p>";
    exit;
}
