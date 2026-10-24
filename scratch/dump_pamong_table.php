<?php
$conn = new mysqli('db', 'root', 'password', 'xosid_tenant_bondo-bangsri');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== ALL PAMONGS IN BONDO ===\n";
$res = $conn->query("SELECT pamong_id, pamong_nama, jabatan_id, id_pend, pamong_status FROM tweb_desa_pamong");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['pamong_id']} | Nama: " . var_export($row['pamong_nama'], true) . " | jabatan_id: " . var_export($row['jabatan_id'], true) . " | id_pend: " . var_export($row['id_pend'], true) . " | status: {$row['pamong_status']}\n";
}
$conn->close();
