<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	

	$data = array();
	$cannotunpost = 0;
	
	try {

		$conn->BeginTrans();
		

		
		$sql = "select * from transaksi_hemoving where hemoving_id='$__ID'";
		$rs  = $conn->Execute($sql);
		$hemovingtype_id = $rs->fields['hemovingtype_id'];		
		$region_id    	 = $rs->fields['region_id'];	
		switch ($hemovingtype_id) {
			case "AJ" :
				$errormessage = "Periode pada tanggal adjustment sudah di close\\r\\nTransaksi tidak bisa di posting untuk tanggal tersebut.";
				$date = $rs->fields['hemoving_date_to'];
				break;
			case "DO" :
				$errormessage = "Periode pada tanggal DO sudah di close\\r\\nTransaksi tidak bisa di posting untuk tanggal tersebut.";
				$date = $rs->fields['hemoving_date_fr'];
				break;
			case "RS" :
				$errormessage = "Periode pada tanggal Personal Order sudah di close\\r\\nTransaksi tidak bisa di posting untuk tanggal tersebut.";
				$date = $rs->fields['hemoving_date_fr'];
				break;	
			default :
				$errormessage = "Transaksi tidak bisa di posting untuk tanggal tersebut.";
				$date = $rs->fields['hemoving_date_to'];
				break;
		}
		
		
		$closingstatus_id = substr($date, 0, 4) . substr($date, 5, 2) . "-" .  $region_id;		
		$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id' AND heinvclosingstatus_iscompleted=1";		
		$rs  = $conn->Execute($SQL);
		if ($rs->recordCount()) {
			throw new Exception($errormessage);
		} 
	


		if ($__ACTION=='POSTALL' || $__ACTION=='UNPOSTALL') {
			if ($__ACTION=='UNPOSTALL') {
				
				//cek apakah masih bisa diunpost
				$failed = 0;
				$POSTVALUE = 0;
				if ($cannotunpost) {
				
					$POSTMSG   = "Data '$__ID' tidak bisa di-unpost!\nKarena sudah di close periodenya.";
					$failed    = 1;
				
				} else {			
				
					$failed = 0;
					$POSTVALUE = 0;
					$POSTMSG   = "Data '$__ID' sudah diUnPost";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->hemoving_isprop = 0;
					$obj->hemoving_propby = '';
					$obj->hemoving_propdate = '__DBNULL__';		
					$obj->hemoving_issend = 0;
					$obj->hemoving_sendby = '';
					$obj->hemoving_senddate = '__DBNULL__';		
					$obj->hemoving_isrecv = 0;
					$obj->hemoving_recvby = '';
					$obj->hemoving_recvdate = '__DBNULL__';		
					$obj->hemoving_ispost = 0;
					$obj->hemoving_postby = '';
					$obj->hemoving_postdate = '__DBNULL__';		
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {
	
					$failed = 0;
					$POSTVALUE = 1;
					$POSTMSG   = "Data '$__ID' sudah di-post";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->hemoving_isprop = 1;
					$obj->hemoving_propby = 'SYSTEM';
					$obj->hemoving_propdate = SQLUTIL::SQL_GetNowDate();
					$obj->hemoving_issend = 1;
					$obj->hemoving_sendby = 'SYSTEM';
					$obj->hemoving_senddate = SQLUTIL::SQL_GetNowDate();
					$obj->hemoving_isrecv = 1;
					$obj->hemoving_recvby = 'SYSTEM';
					$obj->hemoving_recvdate = SQLUTIL::SQL_GetNowDate();
					$obj->hemoving_ispost = 1;
					$obj->hemoving_postby = $username;
					$obj->hemoving_postdate = SQLUTIL::SQL_GetNowDate();					
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);
						
			}
			
			if (!$failed) {
			
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
				$obj->log_table		= "transaksi_hemoving";
				$obj->log_descr		= "ClientIP:".$_POST['__MachineIP'].", ClientName:".$_POST['__MachineName'].", Rmt:".$_SERVER["REMOTE_ADDR"];
				$obj->log_lastvalue	= "";
				$obj->log_username	= $username;
				$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D']['Log']['TABLE_NAME'], $obj);
				$conn->Execute($SQL);
			
			}
			
			
			unset($obj);
			$obj->failed  	= $failed;
			$obj->post  	= $POSTVALUE;
			$obj->message 	= $POSTMSG;
			$data = array($obj);		
			
			
		} else {
			// error, wrong parameter
			$errors = new WebResultErrorObject("0x00000001", "Wrong Parameter for Send/unSent data!! ");
			$objResult = new WebResultObject("objResult");
			$objResult->success = false;
			$objResult->errors = $errors;
			die(stripslashes(json_encode($objResult)));			
		}


		$conn->CommitTrans();
	} catch (Exception $e) {
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