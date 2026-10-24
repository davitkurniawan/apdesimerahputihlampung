<?php
$str = file_get_contents('../donjo-app/controllers/Database.php');
// Is it ioncubed?
if (strpos($str, 'ionCube Loader') !== false) {
    echo "ionCubed";
} else {
    echo "Plain";
}
