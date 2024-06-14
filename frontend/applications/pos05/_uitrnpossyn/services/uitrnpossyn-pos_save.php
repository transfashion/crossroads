<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}


	$__USERNAME	= $_SESSION["username"];
	$__ROWID    = trim($_POST["__ROWID"]);
	$__ID		= trim($_POST["__ID"]);
	$__SYNID    = trim($_POST["__SYNID"]);
	$__JSONDATA	= $_POST['JSONDATA'];
	$__POSTDATA = json_decode(stripslashes($__JSONDATA));
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;


				/* simpan ke log sebagai transaksi konflik */
				$file = "uitrnpossyn.log.txt";
				$fp = fopen($file, "w");
				fputs($fp, "LOGBEGIN~$__ID~$__JSONDATA~LOGEND\n");
				fclose($fp);
				
				

	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-pos_save.php");
	try {

		$sql = "select * from transaksi_postemp WHERE rowid='$__ROWID'";
		$rs = $conn->Execute($sql);
		if ($rs->recordCount()) {
			/* data sudah ada, set untuk diupdate */
			$__POSTDATA->H->__ROWSTATE = 'UPDATE';
			$__ID = $rs->fields['bon_id'];
		} else {
			/* data belum ada, cek apakah bon_id konflik */
			$sql = "select * from transaksi_postemp WHERE bon_id='$__ID'";
			$rs = $conn->Execute($sql);
			if ($rs->recordCount()) {
				/* data konflik, tambahkan time numerator di id */
				$__ID = $__ID.".".time();
				
				/* simpan ke log sebagai transaksi konflik */
				//$file = "uitrnpossyn.log.txt";
				//$fp = fopen($file, "a+");
				//fputs($fp, "LOGBEGIN~$__ID~$__JSONDATA~LOGEND\n");
				//fclose($fp);
			}
		}

		//$conn->BeginTrans();
		include $FileProcessor.'-pos_save-header.php';
		include $FileProcessor.'-pos_save-detil.php';		
		//$conn->CommitTrans();



	} catch (Exception $e) {
		//$conn->RollbackTrans();
		$msg = $e->getMessage();
		$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
		
		/* tulis ke text */
		$file = dirname(__FILE__)."/error.log.txt";
		$fpe = fopen($file, "w");
		fputs($fpe, $msg);
		fclose($fpe);
		
	}
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $__RESULT;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));


?>