<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	

	$sql = "select * from transaksi_hepos where bon_id='$__ID'";
	$rs  = $conn->Execute($sql);
	$bon_date    = $rs->fields['bon_date'];
	$region_id    = $rs->fields['region_id'];		
	$closingstatus_id = substr($bon_date, 0, 4) . substr($bon_date, 5, 2) . "-" .  $region_id;

	/* cek tanggal penerimaan */
	$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id' AND heinvclosingstatus_iscompleted=1";		
	$rs  = $conn->Execute($SQL);
	if ($rs->recordCount()) {
		$cannotunpost = 1;
	} else {			
		$cannotunpost = 0;
	}
	
	try {
		$conn->BeginTrans();

		if ($__ACTION=='VOID' || $__ACTION=='UNVOID') {
			if ($__ACTION=='UNVOID') {
				
				//cek apakah masih bisa diunpost
				$failed = 0;
				$POSTVALUE = 0;
				if ($cannotunpost) {
				
					$POSTMSG   = "Data '$__ID' tidak bisa di-unvoid!\nKarena sudah di close periodenya.";
					$failed    = 1;
				
				} else {			
				
					$failed = 0;
					$VALUE = 0;
					$MSG   = "Data '$__ID' sudah diUnVoid";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->bon_isvoid = 0;
					$obj->bon_voidby = '';
					$obj->bon_voiddate = '__DBNULL__';					
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {
	
					$failed = 0;
					$VALUE = 1;
					$MSG   = "Data '$__ID' sudah di-void";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->bon_isvoid = 1;
					$obj->bon_voidby = $username;
					$obj->bon_voiddate = SQLUTIL::SQL_GetNowDate();					
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
				$obj->log_table		= "transaksi_hepos";
				$obj->log_descr		= "ClientIP:".$_POST['__MachineIP'].", ClientName:".$_POST['__MachineName'].", Rmt:".$_SERVER["REMOTE_ADDR"];
				$obj->log_lastvalue	= "";
				$obj->log_username	= $username;
				$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D']['Log']['TABLE_NAME'], $obj);
				$conn->Execute($SQL);
			
			}
			
			
			unset($obj);
			$obj->failed  	= $failed;
			$obj->voided 	= $VALUE;
			$obj->message 	= $MSG;
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