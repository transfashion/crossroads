<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --
    created by   dwi.atno
    created date 04/05/2011 15:56
inv tester
Filename: uimstctg-delete.php
*/

	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__RESULT 	= array("");	
	$__ACTION	= "DELETE";

	
	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-delete.php");

	try {
		$conn->BeginTrans();
	
		// Hapus semua data yang bersesuaian, kecuali log
		$conn->Execute(sprintf("DELETE FROM %s WHERE %s='%s'", $__CONF['D']['Prop']['TABLE_NAME'], $__CONF['D']['Prop']['PRIMARY_KEY1'], $__ID));	
		$conn->Execute(sprintf("DELETE FROM %s WHERE %s='%s'", $__CONF['H']['TABLE_NAME'], $__CONF['H']['PRIMARY_KEY'], $__ID));


		$SQL = "SELECT line=MAX(log_line) FROM ".$__CONF['D']['Log']['TABLE_NAME']." WHERE ".$__CONF['D']['Log']['PRIMARY_KEY1']." = '$__ID' ";
		$rs  = $conn->Execute($SQL);
		if (!$rs->recordCount()) {
			$LINE = 1;
		} else {
			$LINE = 1 + $rs->fields['line'];
		}
			
		unset($obj);		
		$obj->id			= $__ID;
		$obj->log_line		= $LINE;
		$obj->log_action	= $__ACTION;
		$obj->log_table		= $__CONF['H']['TABLE_NAME'];
		$obj->log_descr		= "ClientIP:".$_POST['__MachineIP'].", ClientName:".$_POST['__MachineName'].", Rmt:".$_SERVER["REMOTE_ADDR"];
		$obj->log_lastvalue	= "";
		$obj->log_username	= $__USERNAME;
		$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D']['Log']['TABLE_NAME'], $obj);
		$conn->Execute($SQL);

			
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