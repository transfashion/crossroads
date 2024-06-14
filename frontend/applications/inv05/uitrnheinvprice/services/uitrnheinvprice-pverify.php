<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	

	$sql = "select * from transaksi_heinvpricedetil where price_id='$__ID'";
	$rs  = $conn->Execute($sql);

	$cannotunverify = 0;
	$cannotverify = 0;

	
	try {
		$conn->BeginTrans();
  
		if ($__ACTION=='VERIFY' || $__ACTION=='UNVERIFY') {
			if ($__ACTION=='UNVERIFY') {
			     
				$SQLP = "Select * from transaksi_heinvprice WHERE price_id='$__ID'";
				$rspos = $conn->execute($SQLP);
				$gen = $rspos->fields['price_isgenerated'];
				if ($gen==1)
				{
					$cannotunverify = 1;
				}

				//cek apakah masih bisa diunpost
				$failed = 0;
				$POSTVALUE = 0;
				if ($cannotunverify==1) {
					$POSTMSG   = "Data '$__ID' tidak bisa di-unverify!\nKarena sudah di Generate.";
					$failed    = 1;
					print $POSTMSG;
				} else {			
					 
					$failed = 0;
					$POSTVALUE = 0;
					$POSTMSG   = "Data '$__ID' sudah di UnVerify";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->price_isverified = 0;
					$obj->price_verifyby='__DBNULL__';
					$obj->price_verifydate='__DBNULL__';
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {

				if ($cannotverify)
				{
					$failed = 1;
					$POSTVALUE = 0;
					$POSTMSG = $_postmsg;
				}
				else
				{
					
					$failed = 0;
					$POSTVALUE = 1;
					$POSTMSG   = "Data '$__ID' sudah di-Verify";								 
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->price_isverified = 1;
					$obj->price_verifyby=$username;
					$obj->price_verifydate=SQLUTIL::SQL_GetNowDate();

					//$obj->price_postdate=getdate();
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);
				}
						
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
				$obj->log_table		= "transaksi_heinvprice";
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
			$errors = new WebResultErrorObject("0x00000001", "Wrong Parameter for Verify/unVerify data!! ");
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