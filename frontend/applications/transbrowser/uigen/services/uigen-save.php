<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__JSONDATA	= $_POST['JSONDATA'];
	$__POSTDATA = json_decode(stripslashes($__JSONDATA));
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;

	
	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-save.php");
	 
	try {
		$conn->BeginTrans();
 
 		include $FileProcessor.'-save_header.php';
		include $FileProcessor.'-save_detilitem-h.php';	
		include $FileProcessor.'-save_detilitem-d1.php';	
		include $FileProcessor.'-save_detilitem-d2.php';	
		include $FileProcessor.'-save_detilitem-d3.php';	
		include $FileProcessor.'-save_detilitem-d4.php';	
		include $FileProcessor.'-save_detilitem-d5.php';	
		include $FileProcessor.'-save_prop.php';		
		
		
		if (empty($__POSTDATA->H)) {
			// Kalo yang diedit cuma detilnya, $__POSTDATA->H nya empty, 
			// sehingga defaultnya, Header tidak diupdate
			// jadi harus diupdate manual untuk headernya		
			unset($obj);
			$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
			$obj->{$__CONF['H']['MODIFYBY']} 	= $__USERNAME;
			$obj->{$__CONF['H']['MODIFYDATE']} 	= SQLUTIL::SQL_GetNowDate();
			$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
			$conn->Execute($SQL);
			$__RESULT[0]->H = $obj;	
			$__EDITDATA = true;
			$__TABLE    = $__CONF['H']['TABLE_NAME']."[detil*]";					
		}  else {
			$__EDITDATA = ($__POSTDATA->H->__ROWSTATE=='NEW') ? false : true;	
			$__TABLE    = $__CONF['H']['TABLE_NAME'];	
		}

		
		// Tulis ke Log
		unset($obj);		
		$SQL = "SELECT line=MAX(log_line) FROM ".$__CONF['D']['Log']['TABLE_NAME']." WHERE ".$__CONF['D']['Log']['PRIMARY_KEY1']." = '$__ID' ";
		$rs  = $conn->Execute($SQL);
		$LINE = !$rs->recordCount() ? 1 :  1 + $rs->fields['line'];
		$obj->id			= $__ID;
		$obj->log_line		= $LINE;
		$obj->log_action	= $__EDITDATA ? "MODIFIED" : "CREATED"; 
		$obj->log_table		= $__TABLE;
		$obj->log_descr		= "ClientIP:".$_POST['__MachineIP'].", ClientName:".$_POST['__MachineName'].", Rmt:".$_SERVER["REMOTE_ADDR"];
		$obj->log_lastvalue	= "";
		$obj->log_username	= $username;
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