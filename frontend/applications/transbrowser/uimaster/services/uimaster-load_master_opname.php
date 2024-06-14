<?
if (!defined('__SERVICE__')) {
	die("access denied");
}


$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'region_id', 'region_id', " %s = '%s' ");
}




$SQL_REGION = "SELECT * FROM transaksi_opnameproject WHERE opnameproject_isdisabled = 0 ";

if ($SQL_CRITERIA) {

	$sql = $SQL_REGION .  " AND ".$SQL_CRITERIA;

} else {
	$sql = $SQL_REGION;
}


$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();

$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->opnameproject_id = $rs->fields['opnameproject_id'];
	$obj->opnameproject_descr = $rs->fields['opnameproject_descr'];
 	$data[] = $obj;
	$rs->MoveNext();
}



$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>