<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	

	$sql = "select * from transaksi_hemoving where hemoving_id='$__ID'";
	$rs  = $conn->Execute($sql);
	$hemoving_isrecv = $rs->fields['hemoving_isrecv'];
	$hemovingtype_id = $rs->fields['hemovingtype_id'];		
			

	try {
		$conn->BeginTrans();
		
		if ($__ACTION=='SEND' || $__ACTION=='UNSEND') {
			if ($__ACTION=='UNSEND') {
				
				//cek apakah masih bisa diunsend
				$failed = 0;
				$SENDVALUE = 0;
				if ($hemoving_isrecv) {
				
					$SENDMSG   = "Data '$__ID' tidak bisa di-unsend!\nKarena sudah di receive.";
					$failed    = 1;
				
				} else {			
				
					$failed = 0;
					$SENDVALUE = 0;
					$SENDMSG   = "Data '$__ID' sudah diUn-Send";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					
					if ($hemovingtype_id=='RV') {
						$obj->hemoving_isprop = 0;
					}				
					$obj->hemoving_issend = 0;
					$obj->hemoving_sendby = '';
					$obj->hemoving_senddate = '__DBNULL__';
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {
	
					$failed = 0;
					$SENDVALUE = 1;
					$SENDMSG   = "Data '$__ID' sudah di-send";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					
					if ($hemovingtype_id=='RV') {
						$obj->hemoving_isprop = 1;
						$obj->hemoving_propby = 'SYSTEM';
						$obj->hemoving_propdate = SQLUTIL::SQL_GetNowDate();
					}
	
					$obj->hemoving_issend = 1;
					$obj->hemoving_sendby = $username;
					$obj->hemoving_senddate = SQLUTIL::SQL_GetNowDate();
					
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
			$obj->sent  	= $SENDVALUE;
			$obj->message 	= $SENDMSG;
			$data = array($obj);		
			
			
		} else {
			// error, wrong parameter
			$errors = new WebResultErrorObject("0x00000001", "Wrong Parameter for Send//unSent data!! ");
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
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>