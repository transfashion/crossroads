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
	

}

unset($errors);
unset($data);



try {

	$criteria = " id='$updater_id' AND branch_id='$branch_id' ";
	$sql = "SELECT * FROM transaksi_heposdataupdatesvrstatus WHERE $criteria ";

	$rs  = $conn->Execute($sql);
	if ($rs->recordCount()) {
		$MODE = "UPDATE";
	} else {
		$MODE = "CREATE";
	}




	$conn->BeginTrans();

	

	

	if ($MODE=="UPDATE") {
			
		if ($confirm) {
			unset($obj);
			$obj->dateend = "".SQLUTIL::SQL_GetNowDate();
			$obj->synsign_id	= "".$synsign_id;
			$obj->machine_id	= "".$machine_id;	
			$obj->iscompleted   = 1;
		} else {
			unset($obj);
			$obj->datestart = SQLUTIL::SQL_GetNowDate();
			$obj->synsign_id	= "".$synsign_id;
			$obj->machine_id	= "".$machine_id;	
		}
		$SQL = SQLUTIL::SQL_UpdateFromObject("transaksi_heposdataupdatesvrstatus", $obj, $criteria);

	} else {
		unset($obj);
		$obj->id 			= "".$updater_id;
		$obj->branch_id		= "".$branch_id;
		$obj->region_id     = "".$region_id;			
		$obj->synsign_id	= "".$synsign_id;
		$obj->machine_id	= "".$machine_id;
		$SQL = SQLUTIL::SQL_InsertFromObject("transaksi_heposdataupdatesvrstatus", $obj);

	}
	$conn->Execute($SQL);


	$conn->CommitTrans();

	$obj->id 			= $updater_id;
	$obj->branch_id		= $branch_id;	
	$obj->synsign_id	= $synsign_id;
	$obj->machine_id	= $machine_id;
	$data[] = $obj;

} catch (exception $e) {
	$conn->RollbackTrans();
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