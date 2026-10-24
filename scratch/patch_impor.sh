#!/bin/bash
sed -i.bak '/$_SESSION\['\''success'\''\] = -1;/i\
    echo "Gagal impor: " . $e->getMessage();\
' xosid_impor_penduduk.php
