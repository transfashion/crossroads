<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__RESULT = array("");	


	
	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-delete.php");

	try {
		$conn->BeginTrans();
	
		// Hapus semua data yang bersesuaian, kecuali log
		$conn->Execute(sprintf("DELETE FROM %s WHERE %s='%s'", $__CONF['D']['DetilRegion']['TABLE_NAME'], $__CONF['D']['DetilRegion']['PRIMARY_KEY1'], $__ID));
		$conn->Execute(sprintf("DELETE FROM %s WHERE %s='%s'", $__CONF['D']['DetilBank']['TABLE_NAME'], $__CONF['D']['DetilBank']['PRIMARY_KEY1'], $__ID));
		$conn->Execute(sprintf("DELETE FROM %s WHERE %s='%s'", $__CONF['D']['DetilContact']['TABLE_NAME'], $__CONF['D']['DetilContact']['PRIMARY_KEY1'], $__ID));
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