<?php
require_once 'xosid_ajax_db.php';
$res = $conn->query("SELECT * FROM tweb_wil_clusterdesa");
echo "Total Rows: " . $res->num_rows . "\n";
while($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Dusun: {$row['dusun']} | RW: {$row['rw']} | RT: {$row['rt']} | urut: {$row['urut']} | id_kepala: {$row['id_kepala']}\n";
}
?>
