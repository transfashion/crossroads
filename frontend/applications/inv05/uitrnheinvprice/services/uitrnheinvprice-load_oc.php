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
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}

	/* Default Criteria  */
	
SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'heorder_id', " %s = '%s' ");
	
 
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'heorder_descr', " (heorder_id='{criteria_value}' OR {db_field} LIKE '%{criteria_value}%') ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_rekanan_id', 'rekanan_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_order_id', 'heorder_id', " %s = '%s' ");
	
	
}



$sql = "

		BEGIN
		
				SET NOCOUNT ON
		
		SELECT 
		heorder_id,
		heorder_date,
		heorder_descr,
		season_id,
		season_name = (select season_name FROM master_season WHERE season_id=transaksi_heorder.season_id),
		rekanan_id,
		rekanan_name = (select rekanan_name FROM master_rekanan WHERE rekanan_id=transaksi_heorder.rekanan_id),
		currency_id
		FROM transaksi_heorder 
		WHERE heorder_isposted=1  AND $SQL_CRITERIA

		END

";




$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->heorder_id = $rs->fields['heorder_id'];
	$obj->heorder_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_date']));
	$obj->heorder_descr = str_replace('"', "", $rs->fields['heorder_descr']);
	$obj->season_id = trim($rs->fields['season_id']);
	$obj->season_name = trim($rs->fields['season_name']);
	$obj->rekanan_id = trim($rs->fields['rekanan_id']);
	$obj->rekanan_name = trim($rs->fields['rekanan_name']);
	$obj->currency_id = trim($rs->fields['currency_id']);
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