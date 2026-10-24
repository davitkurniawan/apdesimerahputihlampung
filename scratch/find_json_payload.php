<?php
$html = file_get_contents('/var/www/html/scratch/response.html');
$parts = explode('<script', $html);
echo "Found " . (count($parts) - 1) . " potential script tags.\n";
foreach ($parts as $index => $part) {
    if ($index === 0) continue;
    $subparts = explode('</script>', $part);
    if (count($subparts) > 1) {
        $scriptContent = $subparts[0];
        // strip attributes if any
        $closingBracePos = strpos($scriptContent, '>');
        if ($closingBracePos !== false) {
            $scriptContent = substr($scriptContent, $closingBracePos + 1);
        }
        echo "Script $index length: " . strlen($scriptContent) . "\n";
        file_put_contents("/var/www/html/scratch/ignition_script_{$index}.js", $scriptContent);
    }
}
