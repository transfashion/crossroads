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
	
	$syn_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'syn_id', 'syn_id', "{criteria_value}");

}


$SQL = "UPDATE transaksi_synserver SET syn_iscompleted=1 WHERE syn_id='$syn_id'";
$conn->Execute($SQL);

$sql = "SELECT * FROM transaksi_synserver WHERE syn_id='$syn_id'";
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