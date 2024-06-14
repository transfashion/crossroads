<?php
$t = $_GET['t'];
$dll_file = $_GET['file'];
$file = "../bin/INV05HERPTSUM02.dll";

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: Transbrowser/library');
    header('Content-Disposition: attachment; filename='.basename($file).'.'.$t);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}

?>