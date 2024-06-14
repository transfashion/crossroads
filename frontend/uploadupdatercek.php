<?
$filename = $_GET['filename'];
print (is_file(dirname(__FILE__)."/updater/$filename")) ? 'OK' : 'NOT FOUND';
?>