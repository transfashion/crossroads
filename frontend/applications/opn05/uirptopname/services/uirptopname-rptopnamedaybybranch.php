<?
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
	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_iteminventorytype_id', 'iteminventorytype_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_iteminventorysubtype_id', 'iteminventorysubtype_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_iteminventory_id', 'opnameproject_id', "refParser");

	//$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
	//$dateend   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
	$coverage = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_coverage', '', "{criteria_value}");
	$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
	
}

$data = array();

$d = explode("-", $datestart);
$day1 = $d[2];

$d = explode("-", $dateend);
$day2 = $d[2];

//print $day2;


for ($i=1; $i<=31; $i++) {
	unset($obj);
	$obj->day = $i;
	$obj->name = "test";
	$data[] = $obj;
}

/* Ambil semua region yang parent nya region_id*/
/*
$sql = "select * from master_region where region_id='$region_id'";
$rs  = $conn->Execute($sql);
$region_path = $rs->fields['region_path'];
$sql = "select * from master_region where region_path like '$region_path%'";
$rs  = $conn->Execute($sql);
$arrregions = array();
while (!$rs->EOF) {
	$arrregions[] = "region_id='".$rs->fields['region_id']."'";
	$rs->MoveNext();
}
$regions_criteria = implode(" OR ", $arrregions);
$SQL_CRITERIA .= "AND (".$regions_criteria.")"; 




$sql = "SELECT * FROM master_iteminventory WHERE $SQL_CRITERIA ORDER BY iteminventory_id DESC";

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->iteminventory_id = $rs->fields['iteminventory_id'];
	$data[] = $obj;
	$rs->MoveNext();
}

*/

$objResult = new WebResultObject("objResult");
$objResult->totalCount = count($data);
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>