<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   dwi.atnorn    created date 01/07/2011 11:11
customer traffic
Filename: uitrncusttraffic-save.php
*/
 
	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__JSONDATA	= $_POST['JSONDATA'];
	$__POSTDATA = json_decode(stripslashes($__JSONDATA));
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;

 
	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-save.php");
 
	try {
		$conn->BeginTrans();
 
 $region_id= $__POSTDATA->H->region_id;
 $branch_id= $__POSTDATA->H->branch_id;
 $custtraffic_date= $__POSTDATA->H->custtraffic_date;

 
 // INI CARA AMBIL QUERY KE DATABASE
$SQL = "select * from transaksi_custtraffic where region_id='$region_id' and branch_id = '$branch_id'
	and custtraffic_date =  '$custtraffic_date' ";
 
// print $custtraffic_date;
 
// INI CARA EKSEKUSI QUERY
$rsCust = $conn->execute($SQL);
  
//INI CARA AMBIL HASIL EKSEKUSI NYA
//KALAU HASILNYA LEBIH DARI SATU, SEHINGGA DIPERLUKAN LOOPING GUNAKAN ---->>>>>   while (!$rs->EOF) {......$rs->MoveNext();}

$custtraffic_id = $rsCust->fields['custtraffic_id'];
$totalCount = $rsCust->recordCount();
 
 //PRINT  '-->' . $custtraffic_id;
 


if ($totalCount>0)
{
		  		    
		  		    if($__ID)
		  		    {
		  		    		$failed    = 0;
							$POSTMSG   = 'BOLEH';
					}
		  		    else
		  		    {
		  		    		$failed    = 1;
							$POSTMSG   = 'Duplicated, Data cannot be saved';
					}
					 
				
}
else
{
 					$failed = 0;
					$POSTMSG   = "BOLEH";
				 
}
  
 
 		include $FileProcessor.'-save_header.php';
		include $FileProcessor.'-save_transaksi_custtrafficdetil.php';       
		include $FileProcessor.'-save_prop.php';		
		
		if (empty($__POSTDATA->H)) {
			// Kalo yang diedit cuma detilnya, $__POSTDATA->H nya empty, 
			// sehingga defaultnya, Header tidak diupdate
			// jadi harus diupdate manual untuk headernya
		 		
			unset($obj);
			$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
			$obj->{$__CONF['H']['MODIFYBY']} 	= $__USERNAME;
			$obj->{$__CONF['H']['MODIFYDATE']} 	= SQLUTIL::SQL_GetNowDate();
			$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
			$conn->Execute($SQL);
			$__RESULT[0]->H = $obj;	
			$__EDITDATA = true;
			$__TABLE    = $__CONF['H']['TABLE_NAME']."[detil*]";					
		}  else {
 
			$__EDITDATA = ($__POSTDATA->H->__ROWSTATE=='NEW') ? false : true;	
			$__TABLE    = $__CONF['H']['TABLE_NAME'];	
		}

		
		// Tulis ke Log
		unset($obj);		
		$SQL = "SELECT line=MAX(log_line) FROM ".$__CONF['D']['Log']['TABLE_NAME']." WHERE ".$__CONF['D']['Log']['PRIMARY_KEY1']." = '$__ID' ";
		$rs  = $conn->Execute($SQL);
		$LINE = !$rs->recordCount() ? 1 :  1 + $rs->fields['line'];
		$obj->id			= $__ID;
		$obj->log_line		= $LINE;
		$obj->log_action	= $__EDITDATA ? "MODIFIED" : "CREATED"; 
		$obj->log_table		= $__TABLE;
		$obj->log_descr		= "ClientIP:".$_POST['__MachineIP'].", ClientName:".$_POST['__MachineName'].", Rmt:".$_SERVER["REMOTE_ADDR"];
		$obj->log_lastvalue	= "";
		$obj->log_username	= $username;
		$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D']['Log']['TABLE_NAME'], $obj);
		$conn->Execute($SQL);
		
		
		$conn->CommitTrans();
		
	} catch (Exception $e) {
		$conn->RollbackTrans();
		$msg = $e->getMessage();
		$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
	}


	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $__RESULT;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));
	
	
?>