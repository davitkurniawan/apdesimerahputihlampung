<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$session_id = '8dfd986939bc4c62711b7855f9c687c3';
$url = "http://localhost/index.php/gawai_layanan/form";

$options = [
    'http' => [
        'header' => "Cookie: ci_session=" . $session_id . "\r\n",
        'method' => 'GET',
        'ignore_errors' => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if (!$response) {
    die("Failed to fetch HTML");
}

$dom = new DOMDocument();
@$dom->loadHTML($response);

$forms = $dom->getElementsByTagName('form');
echo "Found " . $forms->length . " forms.\n";

foreach ($forms as $index => $form) {
    echo "\n--- Form #" . ($index + 1) . " ---\n";
    echo "Action: " . $form->getAttribute('action') . "\n";
    echo "Method: " . $form->getAttribute('method') . "\n";
    echo "Id: " . $form->getAttribute('id') . "\n";
    
    $inputs = $form->getElementsByTagName('input');
    echo "Inputs:\n";
    foreach ($inputs as $input) {
        $name = $input->getAttribute('name');
        $type = $input->getAttribute('type');
        $value = $input->getAttribute('value');
        echo "  - name='{$name}' type='{$type}' value='{$value}'\n";
    }
    
    $selects = $form->getElementsByTagName('select');
    echo "Selects:\n";
    foreach ($selects as $select) {
        $name = $select->getAttribute('name');
        echo "  - name='{$name}':\n";
        $options = $select->getElementsByTagName('option');
        foreach ($options as $opt) {
            if ($opt->hasAttribute('selected')) {
                echo "    * [SELECTED] value='{$opt->getAttribute('value')}' label='{$opt->textContent}'\n";
            } else {
                echo "      value='{$opt->getAttribute('value')}' label='{$opt->textContent}'\n";
            }
        }
    }
}
