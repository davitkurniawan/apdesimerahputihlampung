<?php
$hashes = [
    'dudi' => '$2y$10$FDW5y7UhxIBpLn7J1d7GLekcKIxyTx2L6J.lq.j99iFBIGykJ9vGq',
    'sugiono' => '$2y$10$FYetLQimHqQ4xK/QJOEq4.haH.4UYLFX9VogM4V9iKANnM1l03yXm'
];

$common_pins = ['123456', '111111', '000000', '180110', '123123', '654321'];

foreach ($hashes as $name => $hash) {
    echo "Checking pins for $name...\n";
    foreach ($common_pins as $pin) {
        if (password_verify($pin, $hash)) {
            echo "MATCH FOUND: Pin for $name is '$pin'!\n";
            continue 2;
        }
    }
    echo "No match in common pins.\n";
}
