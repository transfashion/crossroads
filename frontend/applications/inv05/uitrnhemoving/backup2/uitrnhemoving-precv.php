<?
if (!defined('__SERVICE__')) {
	die("access denied");
}


	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	


	$data = array();		

	try {
		$conn->BeginTrans();
	
		$sql = "select * from transaksi_hemoving where hemoving_id='$__ID'";
		$rs  = $conn->Execute($sql);
		$hemovingtype_id = $rs->fields['hemovingtype_id'];
		$hemoving_date_to= $rs->fields['hemoving_date_to'];	
		$hemoving_ispost = $rs->fields['hemoving_ispost'];
		$hemoving_sendby = $rs->fields['hemoving_sendby'];
		$region_id    	 = $rs->fields['region_id'];
		$season_id		 = $rs->fields['season_id'];		
		$date 			 = $rs->fields['hemoving_date_to'];
		
		$rv_update_master = false;
		
	
		$closingstatus_id = substr($date, 0, 4) . substr($date, 5, 2) . "-" .  $region_id;		
		$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id' AND heinvclosingstatus_iscompleted=1";		
		$rs  = $conn->Execute($SQL);
		if ($rs->recordCount()) {
			throw new Exception('Periode pada tanggal penerimaan sudah di close.\nTransaksi tidak bisa di update untuk tanggal tersebut.');
		}

		
		if ($__ACTION=='RECV' || $__ACTION=='UNRECV') {
			if ($__ACTION=='UNRECV') {
				
				//cek apakah masih bisa diunsend
				$failed = 0;
				$RECVVALUE = 0;
				if ($hemoving_ispost) {
				
					$RECVMSG   = "Data '$__ID' tidak bisa di-unreceive!\nKarena sudah di posting.";
					$failed    = 1;
				
				} else {			
				
					$failed = 0;
					$RECVVALUE = 0;
					$RECVMSG   = "Data '$__ID' sudah diUn-Receive";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->hemoving_isrecv = 0;
					$obj->hemoving_recvby = '';
					$obj->hemoving_recvdate = '__DBNULL__';					
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {
			 
			 	// tidak boleh di receive oleh user yang sama
				if ($hemoving_sendby==$username)
				{
					$RECVMSG   = "Data '$__ID' tidak bisa direceive oleh user yang sama! ($username)";
					$failed    = 1;	
					
					throw new Exception("Data '$__ID' tidak bisa direceive oleh user yang sama! ($username)");
								 
				}
				else
				{


					$failed = 0;
					$RECVVALUE = 1;
					$RECVMSG   = "Data '$__ID' sudah di-receive";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->hemoving_isrecv = 1;
					$obj->hemoving_recvby = $username;
					$obj->hemoving_recvdate = SQLUTIL::SQL_GetNowDate();					
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);

					if ($hemovingtype_id=='RV') {
						$rv_update_master = true;
					}


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
				$obj->log_table		= "transaksi_hemoving";
				$obj->log_descr		= "ClientIP:".$_POST['__MachineIP'].", ClientName:".$_POST['__MachineName'].", Rmt:".$_SERVER["REMOTE_ADDR"];
				$obj->log_lastvalue	= "";
				$obj->log_username	= $username;
				$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D']['Log']['TABLE_NAME'], $obj);
				$conn->Execute($SQL);

				

				if ($rv_update_master) {

					$rs = $conn->execute("select heinv_id from transaksi_hemovingdetil where hemoving_id='$__ID'");
					while (!$rs->EOF) {
						$heinv_id = $rs->fields['heinv_id'];
						$heinv_lastrvid = $rs->fields['heinv_lastrvid'];


						/* Update lastrv id dan lastrv date */
						$sql_update = "update master_heinv set heinv_lastrvid='$__ID', heinv_lastrvdate='$hemoving_date_to', heinv_modifyby='$username', heinv_modifydate=getdate() where heinv_id = '$heinv_id' ";
						$conn->Execute($sql_update);
						
						$sql_log = "select line=MAX(log_line) from transaksi_tlog where id='$heinv_id' and log_table='master_heinv'";
						$rs_log  = $conn->Execute($sql_log);
						if (!$rs_log->recordCount()) {
							$LINE = 1;
						} else {
							$LINE = 1 + $rs_log->fields['line'];
						}

						unset($obj);	
						$obj->id			= $heinv_id;
						$obj->log_line		= $LINE;
						$obj->log_action	= "Update Last RV";
						$obj->log_table		= "master_heinv";
						$obj->log_descr		= "$__ID";
						if ($heinv_lastrvid)
							$obj->log_lastvalue	= $heinv_lastrvid;
	
						$obj->log_username	= $username;
						$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D']['Log']['TABLE_NAME'], $obj);
						$conn->Execute($SQL);



						/* Update Season */
						$rs_season = $conn->execute("select heinv_id, season_id from master_heinv where heinv_id='$heinv_id' ");
						if (!$rs_season->recordCount()) {
							$last_season_id = '#NA';
						} else {
							$last_season_id = $rs_season->fields['season_id'];
						}
						

						if ($last_season_id!=$season_id) {

							$sql_update = "update master_heinv set season_id='$season_id', heinv_modifyby='$username', heinv_modifydate=getdate() where heinv_id = '$heinv_id' ";
							$conn->Execute($sql_update);


							$sql_log = "select line=MAX(log_line) from transaksi_tlog where id='$heinv_id' and log_table='master_heinv'";
							$rs_log  = $conn->Execute($sql_log);
							if (!$rs_log->recordCount()) {
								$LINE = 1;
							} else {
								$LINE = 1 + $rs_log->fields['line'];
							}
						
							unset($obj);	
							$obj->id			= $heinv_id;
							$obj->log_line		= $LINE;
							$obj->log_action	= "Update Season";
							$obj->log_table		= "master_heinv";
							$obj->log_descr		= "Season updated to $season_id by $__ID";
							$obj->log_lastvalue	= $last_season_id;
							$obj->log_username	= $username;
							$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D']['Log']['TABLE_NAME'], $obj);
							$conn->Execute($SQL);

						}

						$rs->MoveNext();	
					}

					

				}

			}
			

		    /* notification  */	       
		   if ($__ACTION=='RECV') {
               include dirname(__FILE__)."/uitrnhemoving-notifier.php";
               if ($hemovingtype_id=="RV") 
 		   	   {

                    SendNotificationEmail("RVRECV", $__ID, $region_id, $conn);
				    //print 'imel : ' . $imel;
	            }
	        }



			unset($obj);
			$obj->failed  	= $failed;
			$obj->recv  	= $RECVVALUE;
			$obj->message 	= $RECVMSG;
			$data = array($obj);		
			
			
		} else {
			// error, wrong parameter
			$errors = new WebResultErrorObject("0x00000001", "Wrong Parameter for Receive//unReceive data!! ");
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
