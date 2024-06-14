<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}


$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];


$param = "";
$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$CRITERIA_DB = array();
	while (list($name, $value) = each($objCriteria)) {
		$CRITERIA_DB[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	

        $closingyear   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'closingyear', '', "{criteria_value}");
        $closingmonth   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'closingmonth', '', "{criteria_value}");
		$region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'objRegion', '', "{criteria_value}");
		$closingstatus = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'objclosingstatus', '', "{criteria_value}");
		
}

if (strlen($closingmonth)==1)
{
    $closingmonth = '0' .$closingmonth;
}


$_year = $closingyear;
$_month = $closingmonth;

$heinvclosingstatus_id = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT)."-".$region_id;

$sql = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$heinvclosingstatus_id' ";
$rs  = $conn->Execute($sql);	


$heinvclosingstatus_iscompleted  =1*$rs->fields['heinvclosingstatus_iscompleted'];
 
$_postmsg="";

switch ($closingstatus) 
{
    case "OPEN":
            if ($heinvclosingstatus_iscompleted==0)
            {
                	$_postmsg = "Its Already Open";
                     
            }                
        break;
    CASE "CLOSING" :
            if ($heinvclosingstatus_iscompleted==1)
            {
                	$_postmsg = "Its Already Close";
            }
        break;
}
	$data = array();
    	
    unset($obj);
    $obj->message =$_postmsg;
    $obj->heinvclosingstatus_id =$heinvclosingstatus_id;
    $data[] = $obj;




	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data =  $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));

  


?> 