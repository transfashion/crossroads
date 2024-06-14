<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];
$confirm    = $_GET['confirm'];

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
	$synsign_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'synsign_id', '', "{criteria_value}");
	$updater_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'updater_id', '', "{criteria_value}");
	
	$username = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'username', '', "{criteria_value}");
	$password = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'password', '', "{criteria_value}");
	$bon_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'bon_id', '', "{criteria_value}");
	$systemdate = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'systemdate', '', "{criteria_value}");
	
	
	
	

}

unset($errors);
unset($data);



try {
	
	$md5 = new COM ("IST.DataHash.MD5"); 

	unset($obj);
	$sql = "SELECT * FROM master_user WHERE username='$username' AND user_isdisabled=0 AND user_pos_can_void=1";
	$rs  = $conn->Execute($sql);
	if ($rs->recordCount()) {
		if ($md5->Verify($password, $rs->fields['user_password'])) {
			$obj->canvoid = "1";
		} else {
			$obj->canvoid = "0";
		}	
	} else {
		$obj->canvoid = "0";
	}

	$obj->username 			= ""; //$rs->fields['username'];
	$obj->user_fullname 	= ""; //$rs->fields['user_fullname'];
	$obj->user_password		= $rs->fields['user_password'];
	$data[] = $obj;

	//$conn->BeginTrans();
	//$conn->CommitTrans();

} catch (exception $e) {
	//$conn->RollbackTrans();
	$errors = new WebResultErrorObject("0x00000001", $e->GetMessage()."\nTransaction rollingback");
}

$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = $errors ? false : true;
$objResult->data = $data;
$objResult->errors = $errors;
if (!$errors) unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>