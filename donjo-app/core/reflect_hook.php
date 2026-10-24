<?php
if(isset($_GET['reflect_penduduk'])) {
    require_once APPPATH.'controllers/Penduduk.php';
    echo implode(", ", get_class_methods('Penduduk'));
    exit;
}
