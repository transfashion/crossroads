<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
	}
	
	$region_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'region_id', 'region_id', "{criteria_value}");
	$branch_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'branch_id', 'branch_id', "{criteria_value}");
	$machine_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'machine_id', 'machine_id', "{criteria_value}");
	$lastdate = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'lastdate', 'lastdate', "{criteria_value}");
	$dateclient = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'dateclient', 'dateclient', "{criteria_value}");


}

$id = uniqid("SYN.");
unset($obj);
$obj->syn_id = $id;
$obj->syn_dateclient = $dateclient;
$obj->syn_dateclientlast = $lastdate;
$obj->region_id = $region_id;
$obj->branch_id = $branch_id;
$obj->machine_id = $machine_id;
$SQL = SQLUTIL::SQL_InsertFromObject("transaksi_synserver", $obj);
$conn->Execute($SQL);




$sql = "SELECT * FROM transaksi_synserver WHERE syn_id='$id'";
$rs = $conn->Execute($sql);
$data = array();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->syn_id 			= $rs->fields['syn_id'];
	$obj->syn_date 			= SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['syn_date']));
	$obj->syn_dateserver 	= SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['syn_dateserver']));
	$obj->str_syn_date 		= $rs->fields['syn_date'];
	$obj->str_syn_dateserver= $rs->fields['syn_dateserver'];
	
	$data[] = $obj;
	$rs->MoveNext();
}



$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>