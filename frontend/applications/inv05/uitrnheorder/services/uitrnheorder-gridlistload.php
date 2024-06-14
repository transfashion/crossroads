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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_channel_id', 'channel_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heorder_id', 'heorder_id', "refParser");	

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_rekanan_id', 'rekanan_id', " %s = '%s' ");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_season_id', 'season_id', " %s = '%s' ");	
	
	$TEMP = "";
	$ENTRY = SQLUTIL::BuildCriteria(&$TEMP, $criteria, 'obj_search_chk_heorder_descr', '', "{criteria_value}");
	if (strpos(" ".$ENTRY,"%")) {
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heorder_descr', 'heorder_descr', " {db_field} LIKE '{criteria_value}' ");
	} else {
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heorder_descr', 'heorder_descr', " {db_field} = '{criteria_value}' ");
	}		
	
	$ENTRY = SQLUTIL::BuildCriteria(&$TEMP, $criteria, 'obj_search_chk_heorder_idext', '', "{criteria_value}");
	if (strpos(" ".$ENTRY,"%")) {
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heorder_idext', 'heorder_idext', " {db_field} LIKE '{criteria_value}' ");
	} else {
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heorder_idext', 'heorder_idext', " {db_field} = '{criteria_value}' ");
	}	
	
	
	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heorder_post', 'heorder_isposted', " %s = '%s' ");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heorder_close', 'heorder_isclosed', " %s = '%s' ");	
	
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_heorder WHERE $SQL_CRITERIA ORDER BY heorder_id DESC";
} else {
	$sql = "SELECT * FROM transaksi_heorder ORDER BY heorder_id DESC";
}



$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {

	unset($obj);
	$obj->heorder_id = $rs->fields['heorder_id'];
	$obj->heorder_idext = $rs->fields['heorder_idext'];
	$obj->heorder_source = $rs->fields['heorder_source'];
	$obj->heorder_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_date']));
	$obj->heorder_dateexp = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_dateexp']));
	$obj->heorder_descr = $rs->fields['heorder_descr'];
	$obj->heorder_isdisabled = $rs->fields['heorder_isdisabled'];
	$obj->heorder_isposted = $rs->fields['heorder_isposted'];
	$obj->heorder_isclosed = $rs->fields['heorder_isclosed'];
	$obj->heorder_createby = $rs->fields['heorder_createby'];
	$obj->heorder_createdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_createdate']));
	$obj->heorder_modifyby = $rs->fields['heorder_modifyby'];
	$obj->heorder_modifydate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_modifydate']));
	$obj->region_id = $rs->fields['region_id'];
	$obj->rekanan_id = $rs->fields['rekanan_id'];
	$obj->season_id = $rs->fields['season_id'];
	$obj->currency_id = $rs->fields['currency_id'];
	$obj->channel_id = $rs->fields['channel_id'];



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