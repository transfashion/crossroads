<?
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

	require_once dirname(__FILE__).'/start.inc.php';

	$username = $_POST['username'];
	$password = $_POST['password'];


	$obj->session_id 			= session_id(); 
	$obj->database				= $db[name]."@".addslashes($db[host]);

    $obj->LocalDSNFormat 	= "User ID={0}; Password={1}; Data Source=\\\"{2}\\\"; Initial Catalog={3}; Tag with column collation when possible=False; Use Procedure for Prepare=1; Auto Translate=True; Persist Security Info=True; Provider=\\\"SQLOLEDB.1\\\"; Use Encryption for Data=False; Packet Size=4096";
    $obj->LocalDbUsername	= $db_local[user];
    $obj->LocalDbPassword	= $db_local[pass];
    $obj->LocalDbServer		= $db_local[host];
    $obj->LocalDbname		= $db_local[name];


	$objResult = new WebResultObject("objResult");
	$objResult->success = true;
	$objResult->totalCount  = 1;
	$objResult->data[0] = $obj;
	$objResult->errors	= null;

	print (stripslashes(json_encode($objResult)));

?>