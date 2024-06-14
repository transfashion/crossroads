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
	/*
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_channel_id', 'channel_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemovingtype_id', 'hemovingtype_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_id', 'hemoving_id', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_descr', 'hemoving_descr', " {db_field} LIKE '%{criteria_value}%' ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id_from', 'branch_id_fr', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id_to', 'branch_id_to', " %s = '%s' ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_isprop', 'hemoving_isprop', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_issend', 'hemoving_issend', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_isrecv', 'hemoving_isrecv', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_ispost', 'hemoving_ispost', " %s = '%s' ");


	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_datestart', 'hemoving_date_fr', " convert(varchar(10),hemoving_date_fr,120)>=convert(varchar(10),'{criteria_value}',120) ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_dateend', 'hemoving_date_fr', " convert(varchar(10),hemoving_date_fr,120)<=convert(varchar(10),'{criteria_value}',120) ");

	*/
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transbrowser_uigen WHERE $SQL_CRITERIA ORDER BY uigen_id DESC";
} else {
	$sql = "SELECT * FROM transbrowser_uigen ORDER BY uigen_id DESC";
}

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {

	unset($obj);
	$obj->uigen_id = $rs->fields['uigen_id'];
	$obj->uigen_name = $rs->fields['uigen_name'];
	$obj->uigen_namespace = $rs->fields['uigen_namespace'];
	$obj->uigen_objectname = $rs->fields['uigen_objectname'];
	$obj->uigen_dll = $rs->fields['uigen_dll'];
	$obj->uigen_dataheadertable = $rs->fields['uigen_dataheadertable'];
	$obj->uigen_createby = $rs->fields['uigen_createby'];
	$obj->uigen_createdate = $rs->fields['uigen_createdate'];
	
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