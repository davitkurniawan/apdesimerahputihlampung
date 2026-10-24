import re

with open('xosid_superadmin_api.php', 'r') as f:
    php = f.read()

# Replace the checks in diff
php = php.replace(
    "if (!is_dir($folder_a) || !file_exists($folder_a . '/VERSION')) {",
    "exec('test -d ' . escapeshellarg($folder_a), $tmp, $dir_code);\n        exec('test -f ' . escapeshellarg($folder_a . '/VERSION'), $tmp, $ver_code);\n        if ($dir_code !== 0 || $ver_code !== 0) {"
)

# Fix the VERSION reading at the bottom of diff
php = php.replace(
    "$folder_a_version = trim(file_get_contents($folder_a . '/VERSION'));",
    "exec('cat ' . escapeshellarg($folder_a . '/VERSION') . ' 2>/dev/null', $ver_out);\n        $folder_a_version = trim($ver_out[0] ?? 'Tidak diketahui');"
)

# Apply the same check replace in apply
php = php.replace(
    "if (!is_dir($folder_a) || !file_exists($folder_a . '/VERSION')) {",
    "exec('test -d ' . escapeshellarg($folder_a), $tmp, $dir_code);\n        exec('test -f ' . escapeshellarg($folder_a . '/VERSION'), $tmp, $ver_code);\n        if ($dir_code !== 0 || $ver_code !== 0) {"
)

# Apply the same VERSION read replace in apply
php = php.replace(
    "$version = trim(file_get_contents($folder_a . '/VERSION'));",
    "exec('cat ' . escapeshellarg($folder_a . '/VERSION') . ' 2>/dev/null', $ver_out);\n        $version = trim($ver_out[0] ?? 'Tidak diketahui');"
)

with open('xosid_superadmin_api.php', 'w') as f:
    f.write(php)
