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
	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'price_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'price_descr', " (price_id='{criteria_value}' OR {db_field} LIKE '%{criteria_value}%') ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	//SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_rekanan_id', 'rekanan_id', " %s = '%s' ");
	//SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_order_id', 'heorder_id', " %s = '%s' ");
	
	
}



$sql = "

		BEGIN
		
				SET NOCOUNT ON
		
		SELECT price_id,price_startdate,price_enddate, price_descr FROM TRANsaksi_heinvprice 
		WHERE price_isposted=1  AND $SQL_CRITERIA

		END

";




$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->price_id = $rs->fields['price_id'];
	$obj->price_startdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_startdate']));
	$obj->price_enddate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_enddate']));
	$obj->price_descr = str_replace('"', "", $rs->fields['price_descr']);
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