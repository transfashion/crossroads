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
	$__REGION 	= $_POST['__REGION'];
	$__BRANCH 	= $_POST['__BRANCH'];
	$__DATE 	= $_POST['__DATE'];
	
	
	unset($data);
	
 
$expdate = explode ("/",$__DATE,10); 
$__DATE = substr($expdate[2],0,4) . '-' . $expdate[1] . '-' . $expdate[0];

	
	set_time_limit(100);
	$failed = 0;


	$sql = "select * from transaksi_custtraffic where region_id='$__REGION' and branch_id = '$__BRANCH'
	AND convert(varchar(10),custtraffic_date,120)=convert(varchar(10),'$__DATE',120)";

 
	 $rs  = $conn->Execute($sql);

	
	$custtraffic_id = $rs->fields['$__ID'];
	$totalCount = $rs->recordCount();
	
 
 
if ($totalCount>0)
{
		  		    
		  		    if($__ID)
		  		    {
		  		    		$failed    = 0;
							$POSTMSG   = "BOLEH";
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