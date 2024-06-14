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
	
	/* Default Criteria  	*/
//	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'oc_id', " %s = '%s' ");
//	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'oc_descr', " (oc_id='{criteria_value}' OR {db_field} LIKE '%{criteria_value}%') ");

//	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_rekanan_id', 'rekanan_id', " %s = '%s' ");

	
}


//print $SQL_CRITERIA;
//die();

$sql = "
		SELECT inventorymoving_id,inventorymoving_descr,inventorymoving_date FROM transaksi_inventorymoving WHERE inventorymoving_source='DO_Propose'
		AND inventorymoving_id NOT IN(select inventorymoving_id FROM transaksi_invoice) AND $SQL_CRITERIA 
	   ";



$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->inventorymoving_id = $rs->fields['inventorymoving_id'];
	$obj->inventorymoving_descr = trim($rs->fields['inventorymoving_descr']);
	$obj->inventorymoving_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['inventorymoving_date']));
	$rsI = $conn->Execute($sql);
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