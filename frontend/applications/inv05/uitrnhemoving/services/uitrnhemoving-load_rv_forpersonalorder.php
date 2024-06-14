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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'hemoving_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'hemoving_descr', " (hemoving_id='{criteria_value}' OR {db_field} LIKE '%{criteria_value}%') ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	//SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_rekanan_id', 'rekanan_id', " %s = '%s' ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'chk_month', 'hemoving_date_to', " MONTH(%s) = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'chk_year', 'hemoving_date_to', " YEAR(%s) = '%s' ");	
	
}



$sql = "

	SELECT * FROM transaksi_hemoving 
	WHERE hemovingtype_id = 'RV'
	AND hemoving_ispost='1'
	AND $SQL_CRITERIA

";


//print $sql;


$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->hemoving_id = $rs->fields['hemoving_id'];
	$obj->hemoving_descr = str_replace('"', "", $rs->fields['hemoving_descr']);
	$obj->hemoving_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['hemoving_date']));
	$obj->season_id = trim($rs->fields['season_id']);
	$obj->currency_id = trim($rs->fields['currency_id']);
	$obj->rekanan_id = trim($rs->fields['rekanan_id']);	
	
	

	$sql = "select rekanan_name from master_rekanan where rekanan_id='".$obj->rekanan_id."'";
	$rsI = $conn->Execute($sql);
	$obj->rekanan_name = trim($rsI->fields['rekanan_name']);
		
		
	$obj->branch_id_fr = trim($rs->fields['branch_id_to']);	
	$obj->rate = 1*trim($rs->fields['currency_rate']);		
	$obj->invoice_id = trim($rs->fields['invoice_id']);		
		
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