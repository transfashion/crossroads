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
	$synsign_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'synsign_id', '', "{criteria_value}");
	

}







	$sql = "select top 1 * 
			from dbo.transaksi_heposdataupdatesvr 
			where region_id='$region_id'
	  			and isdisabled=0 
			order by date DESC ";





$rs  = $conn->Execute($sql);
$data = array();
$totalCount = $rs->recordCount();

while (!$rs->EOF) {
	unset($obj);
	$obj->id = $rs->fields['id'];
	$obj->date = $rs->fields['date'];
	$obj->isdisabled = $rs->fields['isdisabled'];
	$obj->region_id = $rs->fields['region_id'];
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