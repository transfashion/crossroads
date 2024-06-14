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
		
		if ($__ACTION=='POST' || $__ACTION=='UNPOST') {
			if ($__ACTION=='UNPOST') {
				
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
					$obj->heinvregister_isposted = 0;
					$obj->heinvregister_postby = '';
					$obj->heinvregister_postdate = '__DBNULL__';					
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {
	
				/* Cek apakah register item bisa di POSING
				Criteria:
				- Size sudah benar
				*/
				$sql = "
					select COUNT(*) as n 
					from transaksi_heinvregisteritem 
					where 
						heinvregister_id = '$__ID'
					and RTRIM(heinv_size)= ''
				";
				$rs  = $conn->Execute($sql);
				$n = $rs->fields['n'];
				if (n>0) {
					throw new Exception('Ada size belum diisi. register tidak bisa disimpan'); 
				}
				


				// cek apakah bisa di post
				$sqlcek = "
					SET NOCOUNT ON;

					DECLARE @heinvregister_id varchar(30);
					SET @heinvregister_id = '$__ID';
					
					BEGIN
						
						CREATE TABLE #temp_barcode (
							heinv_barcode varchar(30),
							n int
						);
						
						INSERT INTO #temp_barcode
						EXEC dbo.inv05he_registerbarcode_check @heinvregister_id;
						

						SET NOCOUNT OFF;
						SELECT * from transaksi_heinvregisteritem 
						where 
							heinvregister_id = @heinvregister_id
						and heinv_barcode in (select heinv_barcode from #temp_barcode);
							
						SET NOCOUNT ON;
						DROP TABLE #temp_barcode;
					END;
				";
				$rs = $conn->Execute($sqlcek);


				$cek = '';
				while (!$rs->EOF) {
					$baris = $rs->fields['heinvregisteritem_line'];
					$heinv_barcode = $rs->fields['heinv_barcode'];

					$cek .= "line $baris barcode $heinv_barcode,  ";
					$rs->MoveNext();
				}		

				if ($cek!='') {
					throw new Exception("Register no barcode salah.    Ada barcode yang TIDAK UNIQUE!!   .Cek data sbb:  $cek");
				}



	
				$failed = 0;
				$POSTVALUE = 1;
				$POSTMSG   = "Data '$__ID' sudah di-post";
				$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
				unset($obj);
				$obj->heinvregister_isposted = 1;
				$obj->heinvregister_postby = $username;
				$obj->heinvregister_postdate = SQLUTIL::SQL_GetNowDate();					
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
				$obj->log_table		= "transaksi_heinvregister";
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
			$errors = new WebResultErrorObject("0x00000001", "Wrong Parameter for Post/unPost data!! ");
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