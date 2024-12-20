<?
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --
    created by   dhewe
    created date 05/08/2011 08:49
Project Management
Filename: uitrnpj-gridlistload.php
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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	*/

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_project_id', 'project_id', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_projecttype_id', 'projecttype_id', "{db_field} = '{criteria_value}'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_project_name', 'project_name', "{db_field} LIKE '%{criteria_value}%'");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', "{db_field} = '{criteria_value}'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id', 'branch_id', "{db_field} = '{criteria_value}'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_strukturunit_id', 'strukturunit_id', "{db_field} = '{criteria_value}'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_project_isposted', 'project_isposted', "{db_field} = '{criteria_value}'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_project_iscompleted', 'project_iscompleted', "{db_field} = '{criteria_value}'");	

	
}



if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_project WHERE $SQL_CRITERIA ORDER BY project_id DESC";
} else {
	$sql = "SELECT * FROM transaksi_project ORDER BY project_id DESC";
}



$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {

	unset($obj);
	$obj->project_id = trim($rs->fields['project_id']);
	$obj->project_rev = (float) trim($rs->fields['project_rev']);
	$obj->project_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['project_date']));
	$obj->project_datesetup = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['project_datesetup']));
	$obj->project_datedue = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['project_datedue']));
	$obj->project_name = trim($rs->fields['project_name']);
	$obj->project_descr = trim($rs->fields['project_descr']);
	$obj->project_location = trim($rs->fields['project_location']);
	$obj->project_isdisabled = trim($rs->fields['project_isdisabled']);
	$obj->project_isposted = trim($rs->fields['project_isposted']);
	$obj->project_isbudgedcommited = trim($rs->fields['project_isbudgedcommited']);
	$obj->project_isschedulecommited = trim($rs->fields['project_isschedulecommited']);
	$obj->project_iscompleted = trim($rs->fields['project_iscompleted']);
	$obj->project_createby = trim($rs->fields['project_createby']);
	$obj->project_createdate = trim($rs->fields['project_createdate']);
	$obj->project_modifyby = trim($rs->fields['project_modifyby']);
	$obj->project_modifydate = trim($rs->fields['project_modifydate']);
	$obj->projecttype_id = trim($rs->fields['projecttype_id']);
	$obj->region_id = trim($rs->fields['region_id']);
	$obj->branch_id = trim($rs->fields['branch_id']);
	$obj->strukturunit_id = trim($rs->fields['strukturunit_id']);
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