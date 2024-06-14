<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	

	$sql = "select * from transaksi_assetrequest where assetrequest_id ='$__ID'";
	$rs  = $conn->Execute($sql);
	$assetrequest_id = $rs->fields['assetrequest_id'];		
			
	
$failed = 1;
	
	try {
		$conn->BeginTrans();

		if ($__ACTION=='POST' || $__ACTION=='UNPOST') {
			if ($__ACTION=='UNPOST') {
				
				//cek apakah masih bisa diunpost

					$failed = 0;
					$POSTVALUE = 0;
					$POSTMSG   = "Data '$__ID' sudah diUnPost";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->assetrequest_isposted = 0;
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				
				
			} 
			else
			{
	
					$failed = 0;
					$POSTVALUE = 1;
					$POSTMSG   = "Data '$__ID' sudah di-post";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->assetrequest_isposted = 1;
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
				$obj->log_table		= "transaksi_assetrequest";
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
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>