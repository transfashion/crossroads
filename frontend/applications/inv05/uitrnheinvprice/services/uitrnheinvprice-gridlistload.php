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
	//@php-ignore
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' "); 
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heprice_id', 'price_id', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_pricingtype_id', 'pricingtype_id', " %s = '%s' ");	

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heprice_descr', 'price_descr', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heprice_refid', 'ref_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_isPosted', 'price_isposted', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_isVerified', 'price_isverified', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_isGenerated', 'price_isgenerated', " %s = '%s' ");

	
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_heinvprice WHERE $SQL_CRITERIA ORDER BY price_id DESC";
} else {
	$sql = "SELECT * FROM transaksi_heinvprice ORDER BY price_id DESC";
}

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) { 
	unset($obj);
	$obj->price_id = $rs->fields['price_id'];
	$obj->price_descr = $rs->fields['price_descr'];
	$obj->pricingtype_id = $rs->fields['pricingtype_id'];
	$obj->price_startdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_startdate']));
	$obj->price_enddate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_enddate']));
	$obj->region_id = $rs->fields['region_id'];
	$obj->price_isposted = 1*$rs->fields['price_isposted'];
	$obj->price_isverified = 1*$rs->fields['price_isverified'];
	$obj->price_isgenerated = 1*$rs->fields['price_isgenerated'];
	$obj->ref_id = $rs->fields['ref_id'];
	
	$sqlR = "SELECT region_name FROM master_region WHERE region_id =$obj->region_id";
	$rsR = $conn->Execute($sqlR);
	$obj->region_name = $rsR->fields['region_name'];
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