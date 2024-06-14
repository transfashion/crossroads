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
	
		$region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'region_id', '', "{criteria_value}");
		$heinv_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'heinv_id', '', "{criteria_value}");
        $thn   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'thn', '', "{criteria_value}");
        $bln   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'bln', '', "{criteria_value}");
		
}



    $bln =  str_pad($bln, 2, "0", STR_PAD_LEFT);
    $heinvclosingstatus_id = $thn.$bln.'-'.$region_id;


$SQL = "SELECT * FROM transaksi_heinvclosingstatuscostcorrection WHERE     heinv_id = '$heinv_id'";
$rs = $conn->execute($SQL);


$data = array();
 while(!$rs->EOF)
 {
    unset($obj);
    $obj->heinvclosingstatus_id = $rs->fields['heinvclosingstatus_id'];
    $obj->heinv_id = $rs->fields['heinv_id'];
    $obj->COST = (float) $rs->fields['COST'];
    
    $data[] = $obj;
    $rs->MoveNext();
 }
 

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data =  $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));
 
?> 