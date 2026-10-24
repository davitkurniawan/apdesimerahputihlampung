<?php
// We will fetch the login page to get the CSRF token and session cookie, then submit the form!
$html = file_get_contents('http://localhost:8080/xoSID-login');
preg_match('/name="sidcsrf"\s+value="(.*?)"/', $html, $matches);
$csrf = $matches[1] ?? '';
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $http_response_header_string ?? '', $cookies); // Actually file_get_contents doesn't return headers easily.
