<?php
define('BASEPATH', 'test');
require_once '../donjo-app/config/database.php';

session_start();

// Simulating the CodeIgniter session isn't directly possible like this because CI uses its own session handler
// Let's just read the CI session file from the PHP session storage path or CI session cookie.
// Actually, since CI uses standard sessions in some configs or file-based sessions in `ci_sessions`, 
// let's just grep the database or storage for session data!

$dsn = 'mysql:host='.$db['default']['hostname'].';dbname='.$db['default']['database'];
$user = $db['default']['username'];
$pass = $db['default']['password'];

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check ci_sessions table if it exists
    $stmt = $pdo->query("SELECT data FROM ci_sessions ORDER BY timestamp DESC LIMIT 5");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row) {
        if (strpos($row['data'], 'pesan') !== false || strpos($row['data'], 'sukses') !== false || strpos($row['data'], 'error') !== false) {
            echo "Session Data: " . $row['data'] . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
