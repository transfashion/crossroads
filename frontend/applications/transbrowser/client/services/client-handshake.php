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

	$region_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'region_id', '', "{criteria_value}");
	$branch_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'branch_id', '', "{criteria_value}");
	$machine_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'machine_id', '', "{criteria_value}");
	$client_date = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'client_date', '', "{criteria_value}");
	$synsign_type = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'synsign_type', '', "{criteria_value}");
	$synsign_note = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'synsign_note', '', "{criteria_value}");

}




$synsign_id = $region_id.".".$branch_id.".".$machine_id."-".uniqid();
unset($obj);
$obj->synsign_id			= $synsign_id;
$obj->synsign_type			= $synsign_type;
$obj->synsign_dateclient	= $client_date;
$obj->synsign_ip			= $_SERVER["REMOTE_ADDR"];
$obj->synsign_hostname		= $_POST['__MachineName']; 
$obj->synsign_rmtip			= $_POST['__MachineIP'];
$obj->session_id			= "";
$obj->synsign_note			= $synsign_note;
$obj->region_id				= $region_id;
$obj->branch_id				= $branch_id;
$obj->username				= $username;
$SQL = SQLUTIL::SQL_InsertFromObject("transaksi_hepossynsignsvr", $obj);
$conn->Execute($SQL);

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
	$obj->synsign_note = $rs->fields['synsign_note'];
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