<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	

	$sql = "select * from transaksi_hemoving where hemoving_id='$__ID'";
	$rs  = $conn->Execute($sql);
	$hemoving_issend = $rs->fields['hemoving_issend'];
	$hemovingtype_id = $rs->fields['hemovingtype_id'];		
			


			
	try {
		$conn->BeginTrans();
	
		if ($__ACTION=='PROP' || $__ACTION=='UNPROP') {
			if ($__ACTION=='UNPROP') {
				
				//cek apakah masih bisa diunsend
				$failed = 0;
				$PROPVALUE = 0;
				if ($hemoving_issend) {
				
					$PROPMSG   = "Data '$__ID' tidak bisa di-unprop!\nKarena sudah di send.";
					$failed    = 1;
				
				} else {			
				
					$failed = 0;
					$PROPVALUE = 0;
					$PROPMSG   = "Data '$__ID' sudah diUn-Prop";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->hemoving_isprop = 0;
					$obj->hemoving_propby = '';
					$obj->hemoving_propdate = '__DBNULL__';
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {
	
					$failed = 0;
					$PROPVALUE = 1;
					$PROPMSG   = "Data '$__ID' sudah di-prop";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->hemoving_isprop = 1;
					$obj->hemoving_propby = $username;
					$obj->hemoving_propdate = SQLUTIL::SQL_GetNowDate();
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
			$obj->prop  	= $PROPVALUE;
			$obj->message 	= $PROPMSG;
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