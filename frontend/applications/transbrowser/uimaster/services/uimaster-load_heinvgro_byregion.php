<?
if (!defined('__SERVICE__')) {
	die("access denied");
}




$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
	}

	/* parsing criteria */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'heinvgro_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'heinvgro_name', " ( {db_field} LIKE '%{criteria_value}%' OR heinvgro_id = '{criteria_value}' )");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_cbo_region_id', 'region_id', " %s = '%s' ");	

}



if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_heinvgro WHERE $SQL_CRITERIA ORDER BY heinvgro_name DESC";
} else {
	$sql = "SELECT * FROM master_heinvgro ORDER BY heinvgro_name DESC";
}


//print $sql;

$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->heinvgro_id = $rs->fields['heinvgro_id'];
	$obj->heinvgro_name = str_replace('"', "", $rs->fields['heinvgro_name']);
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