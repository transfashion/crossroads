<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   dwi.atnorn    created date 01/07/2011 11:11
customer traffic
Filename: uitrncusttraffic-savecheck.php
*/



if (!defined('__SERVICE__')) {
	die("access denied");
}
	$username 	= $_SESSION["username"];
	$__ID		= $_POST['__ID'];
	$__NAME		= $_POST['__NAME'];
	$__REGION	= $_POST['__REGION'];
		
	unset($data);
	
	
	set_time_limit(100);
	$failed = 0;


	$sql = "select * from master_heinvctg where heinvctg_id='$__ID' and region_id = '$__REGION'"; 

	$rs  = $conn->Execute($sql);

	
	$heinvctg_id = $rs->fields['$__ID'];
	$totalCount = $rs->recordCount();
	
 
 
if ($totalCount>0)
{
	  		
	$failed    = 1;
	$POSTMSG   = 'ID Duplicated, Data cannot be saved';
				
}
else
{



 	$sql = "select * from master_heinvctg where heinvctg_name='$__NAME' and region_id = '$__REGION'"; 
	$rs  = $conn->Execute($sql);

	$totalCount = $rs->recordCount();

 	if ($totalCount==0)	
	{			$failed = 0;
				$POSTMSG   = "BOLEH";
	}		
	else
	{
				$failed    = 1;
				$POSTMSG   = 'NAME Duplicated, Data cannot be saved';
	}			 
}
  
 
			unset($obj);
			$obj->failed  	= $failed;
			$obj->message 	= $POSTMSG;
			$data = array($obj);	
				
				
				
$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
 
print(stripslashes(json_encode($objResult)));


?>