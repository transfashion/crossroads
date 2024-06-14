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

	$synsign_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'synsign_id', '', "{criteria_value}");

}


$sql = "UPDATE transaksi_hepossynsignsvr SET synsign_iscompleted=1 WHERE synsign_id='$synsign_id' ";
$conn->Execute($sql);

unset($data);
$sql = "SELECT * FROM transaksi_hepossynsignsvr WHERE synsign_id='$synsign_id' ";
$rs  = $conn->Execute($sql);
while (!$rs->EOF) {
	unset($obj);
	$obj->synsign_id = $rs->fields['synsign_id'];
	$obj->synsign_type = $rs->fields['synsign_type'];
	$obj->synsign_dateserver = $rs->fields['synsign_dateserver'];
	$obj->synsign_dateclient = $rs->fields['synsign_dateclient'];
	$obj->synsign_ip = $rs->fields['synsign_ip'];
	$obj->synsign_hostname = $rs->fields['synsign_hostname'];
	$obj->synsign_rmtip = $rs->fields['synsign_rmtip'];
	$obj->session_id = $rs->fields['session_id'];
	$obj->region_id = $rs->fields['region_id'];
	$obj->branch_id = $rs->fields['branch_id'];	
	$obj->username = $rs->fields['username'];	
	$obj->rowid = $rs->fields['rowid'];	
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