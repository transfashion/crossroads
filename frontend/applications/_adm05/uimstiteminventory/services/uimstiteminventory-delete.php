<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__RESULT = array("");	


	
	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-delete.php");

	$SQL = "SELECT * FROM transaksi_ocdetil WHERE iteminventory_id = '$__ID'";
	$rs = $conn->Execute($SQL);
	
	if 	($rs->recordCount ())
	{
		$errors = new WebResultErrorObject("0x00000001", "Can Not Delete '$__ID', it has been used for PURCHASE transaction");		
		$objResult = new WebResultObject("objResult");
		$objResult->totalCount = 1;
		$objResult->success = false;
		$objResult->data = $__RESULT;
		$objResult->errors = $errors;
		print(stripslashes(json_encode($objResult)));
		die();
	}

	$SQL = "SELECT * FROM transaksi_postempdetil WHERE bondetil_item = '$__ID'";
	$rs = $conn->Execute($SQL);
	
	if 	($rs->recordCount ())
	{
		$errors = new WebResultErrorObject("0x00000001", "Can Not Delete '$__ID', it has been used for SALES transaction");		
		$objResult = new WebResultObject("objResult");
		$objResult->totalCount = 1;
		$objResult->success = false;
		$objResult->data = $__RESULT;
		$objResult->errors = $errors;
		print(stripslashes(json_encode($objResult)));
		die();
	}
	

	$SQL = "SELECT * FROM transaksi_inventorymovingdetil WHERE iteminventory_id = '$__ID'";
	$rs = $conn->Execute($SQL);
	
	if 	($rs->recordCount ())
	{
		$errors = new WebResultErrorObject("0x00000001", "Can Not Delete '$__ID', it has been used for INVENTORY transaction");		
		$objResult = new WebResultObject("objResult");
		$objResult->totalCount = 1;
		$objResult->success = false;
		$objResult->data = $__RESULT;
		$objResult->errors = $errors;
		print(stripslashes(json_encode($objResult)));
		die();
	}
	
	
	


	try {
		$conn->BeginTrans();
	
		// Hapus semua data yang bersesuaian, kecuali log
		$conn->Execute(sprintf("DELETE FROM %s WHERE %s='%s'", $__CONF['D']['Prop']['TABLE_NAME'], $__CONF['D']['Prop']['PRIMARY_KEY1'], $__ID));
		$conn->Execute(sprintf("DELETE FROM %s WHERE %s='%s'", $__CONF['H']['TABLE_NAME'], $__CONF['H']['PRIMARY_KEY'], $__ID));
			
		$conn->CommitTrans();
	} catch (Exception $e) {
		$conn->RollbackTrans();
		$msg = $e->getMessage();
		$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
	}


	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $__RESULT;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));
	
	
?>