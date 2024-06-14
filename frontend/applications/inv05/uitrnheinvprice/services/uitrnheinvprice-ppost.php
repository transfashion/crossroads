<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	

	$sql = "select * from transaksi_heinvpricedetil where price_id='$__ID'";
	$rs  = $conn->Execute($sql);

	$cannotunpost = 0;
	$cannotpost = 0;
	
	WHILE (!$rs->EOF)
	{
		$heinv_id = $rs->Fields['heinv_id'];
		
		$heinv_art = $rs->fields['heinv_art'];
		$heinv_mat = $rs->fields['heinv_mat'];
		$heinv_col = $rs->fields['heinv_col'];
		$heinvgro_id = $rs->fields['heinvgro_id'];
		$heinvctg_id = $rs->fields['heinvctg_id'];
		
		$sql_cek = "SELECT * FROM master_heinv WHERE heinv_id = '$heinv_id'";
		$rsCek = $conn->Execute($sql_cek);
		
		$heinv_art_cek = $rsCek->fields['heinv_art'];
		$heinv_mat_cek = $rsCek->fields['heinv_mat'];
		$heinv_col_cek = $rsCek->fields['heinv_col'];
		$heinvgro_id_cek = $rsCek->fields['heinvgro_id'];
		$heinvctg_id_cek = $rsCek->fields['heinvctg_id'];
		
		
		IF (($heinv_art!=$heinv_art_cek))
		{
		 		$_postmsg   = "Data '$__ID' tidak bisa di-post!\nKarena Ada ke tidak cocokan Data.";
		 }
	
		IF (($heinv_mat!=$heinv_mat_cek))
		{
		 		$_postmsg   = "Data '$__ID' tidak bisa di-post!\nKarena Ada ke tidak cocokan Data.";
		 }
	
		IF (($heinv_col!=$heinv_col_cek))
		{
		 		$_postmsg   = "Data '$__ID' tidak bisa di-post!\nKarena Ada ke tidak cocokan Data.";
		 }
	
		IF (($heinvgro_id!=$heinvgro_id_cek))
		{
		 		$_postmsg   = "Data '$__ID' tidak bisa di-post!\nKarena Ada ke tidak cocokan Data.";
		 }
	
		IF (($heinvctg_id!=$heinvctg_id_cek))
		{
		 		$_postmsg   = "Data '$__ID' tidak bisa di-post!\nKarena Ada ke tidak cocokan Data.";
		 }

	 	
	 	$rs->MoveNext();
	 }

	





	
	try {
		$conn->BeginTrans();
  
		$SQLP = "Select * from transaksi_heinvprice WHERE price_id='$__ID'";
		$rspos = $conn->execute($SQLP);


		if ($__ACTION=='POST' || $__ACTION=='UNPOST') {
			if ($__ACTION=='UNPOST') {
				$gen = $rspos->fields['price_isverified'];
				if ($gen==1)
				{
					$cannotunpost = 1;
				}

				//cek apakah masih bisa diunpost
				$failed = 0;
				$POSTVALUE = 0;
				if ($cannotunpost==1) {
					$POSTMSG   = "Data '$__ID' tidak bisa di-unpost!\nKarena sudah di Verifikasi.";
					$failed    = 1;
					print $POSTMSG;
				} else {			
					 
					$failed = 0;
					$POSTVALUE = 0;
					$POSTMSG   = "Data '$__ID' sudah di UnPost";
					$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
					unset($obj);
					$obj->price_isposted = 0;
					$obj->price_postby='__DBNULL__';
					$obj->price_postdate='__DBNULL__';
					$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
					$conn->Execute($SQL);			
				
				}
				
			} else {
				$pricingtype_id = $rspos->fields['pricingtype_id'];
				$calculated = $rspos->fields['price_iscalculated'];
				
				/*
				if ($calculated==0)
				{
					if ($pricingtype_id=='REG') {
						$cannotpost = 1;
						$_postmsg = 'Cost Data pricing belum di kalkulasi';
					}
				}
				*/

				if ($cannotpost==1)
				{
					$failed = 1;
					$POSTVALUE = 0;
					$POSTMSG = $_postmsg;
					print $POSTMSG;
				}
				else
				{
					
						$failed = 0;
						$POSTVALUE = 1;
						$POSTMSG   = "Data '$__ID' sudah di-post";								 
						$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
						unset($obj);
						$obj->price_isposted = 1;
						$obj->price_postby=$username;
						$obj->price_postdate=SQLUTIL::SQL_GetNowDate();

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