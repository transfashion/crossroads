<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__JSONDATA	= $_POST['JSONDATA'];
	$__MachineIP = $_POST['__MachineIP'];
	$__MachineName = $_POST['__MachineName'];
	$__POSTDATA = json_decode(stripslashes($__JSONDATA));
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;
	

	
	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-save.php");
	 
	try {
	
		$conn->BeginTrans();

 
		include $FileProcessor.'-save_header.php';
		include $FileProcessor.'-save_detilitem.php';		
		include $FileProcessor.'-save_detilpaym.php';	
		include $FileProcessor.'-save_prop.php';
	
	
	
		/* Cek apakah transaksi valid, dan di bulan yang belum di close */
		$SQL = "SELECT * FROM transaksi_hepos WHERE bon_id='$__ID' ";
		$rs  = $conn->Execute($SQL);
		$bon_date    = $rs->fields['bon_date'];
		$region_id    = $rs->fields['region_id'];		
		$closingstatus_id = substr($bon_date, 0, 4) . substr($bon_date, 5, 2) . "-" .  $region_id;

		/* cek tanggal penerimaan */
		$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id' AND heinvclosingstatus_iscompleted=1";		
		$rs  = $conn->Execute($SQL);
		if ($rs->recordCount()) {
			throw new Exception('Periode pada tanggal penerimaan sudah di close.\nTransaksi tidak bisa di simpan untuk tanggal tersebut.');
		} 
			
			
		
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