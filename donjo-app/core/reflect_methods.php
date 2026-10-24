<?php
if(isset($_GET['reflect_penduduk']) && class_exists('Penduduk')) {
    echo "METHODS: " . implode(", ", get_class_methods('Penduduk'));
    exit;
}
