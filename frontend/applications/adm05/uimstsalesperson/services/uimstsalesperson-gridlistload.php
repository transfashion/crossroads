<?
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --
    created by   fakhri.reza
    created date 21/05/2012 13:21
POS Sales Person
Filename: uimstsalesperson-gridlistload.php
*/



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
	/*
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_channel_id', 'channel_id', " %s = '%s' ");
	
	*/

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_possalesperson_id', 'possalesperson_id', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', "{db_field} LIKE '%{criteria_value}%'");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_nik', 'nik', "{db_field} LIKE '%{criteria_value}%'");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_possalesperson_name', 'possalesperson_name', "{db_field} LIKE '%{criteria_value}%'");
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_possalesperson WHERE $SQL_CRITERIA ORDER BY possalesperson_id DESC";
} else {
	$sql = "SELECT * FROM master_possalesperson ORDER BY possalesperson_id DESC";
}



$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {

	unset($obj);
	$obj->possalesperson_id = trim($rs->fields['possalesperson_id']);
	$obj->possalesperson_name = trim($rs->fields['possalesperson_name']);
	$obj->possalesperson_isdisabled = trim($rs->fields['possalesperson_isdisabled']);
	$obj->region_id = trim($rs->fields['region_id']);
	$obj->nik = trim($rs->fields['nik']);
	$obj->possalesperson_createby = trim($rs->fields['possalesperson_createby']);
	$obj->possalesperson_createdate = trim($rs->fields['possalesperson_createdate']);
	$obj->possalesperson_modifyby = trim($rs->fields['possalesperson_modifyby']);
	$obj->possalesperson_modifydate = trim($rs->fields['possalesperson_modifydate']);
	$obj->rowid = trim($rs->fields['rowid']);

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