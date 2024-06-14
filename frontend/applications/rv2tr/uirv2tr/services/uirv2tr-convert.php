<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}


$username = $_SESSION["username"];
$RV = $_POST['RV'];
$TR = $_POST['TR'];

$data = array();


try
{
	$fail = false;
	$message = "";


	$conn->BeginTrans();


	$sql = "SELECT * FROM transaksi_hemoving WHERE hemovingtype_id='RV' AND hemoving_id='$RV'";
	$rs  = $conn->Execute($sql);
	
	if (!$rs-recordCount())
	{
		$fail = true;
		$message = "No RV '$RV' tidak ditemukan!";
	}
		
	
	
	
	unset($obj);
	$obj->fail  	= $fail;
	$obj->message 	= $message;
	$data = array($obj);	

	$conn->CommitTrans();
	
} 
catch (Exception $e) 
{
	$conn->RollbackTrans();
	$msg = $e->getMessage();
	$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
}		
		
	
$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
$objResult->errors = $dbErrors;
if (!$dbErrors) unset($objResult->errors);
			
print(stripslashes(json_encode($objResult)));



?>