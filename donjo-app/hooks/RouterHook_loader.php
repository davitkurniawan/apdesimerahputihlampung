<?php

// Load the original ionCube-encrypted RouterHook from backup
if (file_exists(__DIR__ . '/RouterHook_ioncube.php')) {
    require_once __DIR__ . '/RouterHook_ioncube.php';
}

// If the ionCube class was not loaded (e.g. ionCube not installed),
// define a stub so the system doesn't crash
if (!class_exists('RouterHookOriginal', false)) {
    // The ioncube file defines 'RouterHook' — we need to alias it
    // so we can redefine RouterHook as our wrapper
}
