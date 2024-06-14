<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];

$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heprintbarcode_id', 'batch_id', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heprintbarcode_datestart', 'batch_date', " %s = '%s' ");	

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heprintbarcode_descr', 'batch_descr', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heprice_refid', 'ref_id', " %s = '%s' ");



	
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_heinvprintbarcode WHERE $SQL_CRITERIA ORDER BY batch_id DESC";
} else {
	$sql = "SELECT * FROM transaksi_heinvprintbarcode ORDER BY batch_id DESC";
}
$rs = $conn->Execute($sql);

$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) { 
	unset($obj);
	$obj->batch_id = $rs->fields['batch_id'];
	$obj->batch_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['batch_date']));
	$obj->region_id = $rs->fields['region_id'];	
	$obj->batch_descr = $rs->fields['batch_descr'];
	$obj->batch_isposted = 1*$rs->fields['batch_isposted'];
	
	$sqlR = "SELECT region_name FROM master_region WHERE region_id =$obj->region_id";
	$rsR = $conn->Execute($sqlR);
	$obj->region_name = $rsR->fields['region_name'];
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