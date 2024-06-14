<?php

require_once dirname(__FILE__)."/frontend/adodb/adodb.class.php";
require_once dirname(__FILE__)."/frontend/adodb/adodb-exceptions.inc.php";

echo "test koneksi ke SQLite";


try {
    $cachefile = 'SL.01100.0000600.20190717.636989741720038362.db';
	$datafile = dirname(__FILE__)."/../data/sales/".$cachefile;
	if (!is_file($datafile)) throw new Exception("$datafile is not a file");    

    echo "test koneksi ke $cachefile\r\n";

    $sqliteconn = &ADONewConnection('sqlite');
	$sqliteconn->Connect($datafile);



} catch (Exception $e) {
    echo "ERROR\r\n";
    echo $e->getMessage();

}