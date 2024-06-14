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
	$__MISC     = $__POSTDATA[1];
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;
	

	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-save.php");
	 
	try {
		$conn->BeginTrans();
 
		include $FileProcessor.'-save_header.php';
		include $FileProcessor.'-save_detilitem.php';		
		include $FileProcessor.'-save_detilexcp.php';	
		include $FileProcessor.'-save_prop.php';
			

		/* Cek apakah transaksi valid, dan di bulan yang belum di close */
		$SQL = "SELECT * FROM transaksi_hemoving WHERE hemoving_id='$__ID' ";
		$rs  = $conn->Execute($SQL);
		$hemovingtype_id = $rs->fields['hemovingtype_id'];	
		$date_send    	= $rs->fields['hemoving_date_fr'];
		$date_recv 	= $rs->fields['hemoving_date_to'];
		$is_send	= $rs->fields['hemoving_issend'];
		$is_recv	= $rs->fields['hemoving_isrecv'];
		$region_id    	= $rs->fields['region_id'];		
		$closingstatus_id_send = substr($date_send, 0, 4) . substr($date_send, 5, 2) . "-" .  $region_id;
		$closingstatus_id_recv = substr($date_recv, 0, 4) . substr($date_recv, 5, 2) . "-" .  $region_id;
		$branch_id_to = $rs->fields['branch_id_to'];
		$branch_id_fr = $rs->fields['branch_id_fr'];
	

		//

		switch ($hemovingtype_id) {
			case "TR" :
				//tahan semua TR dari dan ke CWS
				if ($branch_id_to=='0002300' || $branch_id_fr=='0002300') {
					//echo "hold: ----$hemovingtype_id---$branch_id_to ---- $branch_id_fr ";
					$hold_date_start = new DateTime('2017-02-13 00:00:00');
					$hold_date_end = new DateTime('2017-02-13 23:59:59');
					$currdate = new DateTime();
					if ($currdate>=$hold_date_start && $currdate<=$hold_date_end)
						throw new Exception("Transaksi transfer dari dan atau ke CWS di hold untuk sementara, guna keperluan audit.");
				}
				break;

			case "AJ" :
				$errormessage = 'Periode pada tanggal adjustment (to) sudah di close.\nTransaksi tidak bisa di simpan untuk tanggal tersebut.';
				break;
			case "DO" :
				$errormessage = 'Periode pada tanggal DO (fr) sudah di close.\nTransaksi tidak bisa di simpan untuk tanggal tersebut.';
				break;
			case "RS" :
				$errormessage = 'Periode pada tanggal Personal Order (fr) sudah di close.\nTransaksi tidak bisa di simpan untuk tanggal tersebut.';
				break;
			case "RV" :
				if ($__MISC->DataProperties->ProgramSource=="RV_Cost") {
					$heinvclosingstatus_save_check = "RV_Cost";
				}



		}
 
 
		switch ($heinvclosingstatus_save_check) {
		
			case "RV_Cost" :
				$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id_recv' AND heinvclosingstatus_iscompleted=1";		
				$rs  = $conn->Execute($SQL);
				if ($rs->recordCount()) {
					throw new Exception('Periode pada tanggal costing sudah di close.\nTransaksi tidak bisa di simpan untuk tanggal tersebut.');
				}			
				break;


			default :
				if (!$is_recv) {
					/* cek tanggal penerimaan */
					
					
					$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id_recv' AND heinvclosingstatus_iscompleted=1";		
					$rs  = $conn->Execute($SQL);
					if ($rs->recordCount()) {
						$errormessage = $errormessage ? $errormessage : 'Periode pada tanggal penerimaan sudah di close.\nTransaksi tidak bisa di simpan untuk tanggal tersebut.';
						throw new Exception($errormessage);
					} else {
						
						if (!$is_send) {
							/* cek tanggal pengiriman */
							$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id_send' AND heinvclosingstatus_iscompleted=1";		
							$rs  = $conn->Execute($SQL);
							if ($rs->recordCount()) {
								$errormessage = $errormessage ? $errormessage : 'Periode pada tanggal pengiriman sudah di close.\nTransaksi tidak bisa di simpan untuk tanggal tersebut.';
								throw new Exception($errormessage);
							}
						}
					
					}
				}
				break;
									
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
