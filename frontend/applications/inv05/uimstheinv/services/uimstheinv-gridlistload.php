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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_season_id', 'season_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinv_id', 'heinv_id', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinv_art', 'heinv_art', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinv_mat', 'heinv_mat', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinv_col', 'heinv_col', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinv_col', 'heinv_col', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_type', 'heinv_gtype', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinv_isdisabled', 'heinv_isdisabled', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinvgro_id', 'heinvgro_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_heinvctg_id', 'heinvctg_id', " %s = '%s' ");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_unsetprice', '', " heinv_price01=0 ");	





}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_heinv WHERE $SQL_CRITERIA ";
} else {
	$sql = "SELECT * FROM master_heinv ";
}




$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {


	unset($obj);
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = $rs->fields['heinv_name'];
	$obj->heinv_price = (float) $rs->fields['heinv_price01'];
	$obj->heinv_pricedisc = (int) $rs->fields['heinv_pricedisc01'];
	$obj->heinv_pricenett = (float) $rs->fields['heinv_price01'] * ((100-(int) $rs->fields['heinv_pricedisc01'])/100);



	//$obj->heinv_pricedisc = 0;
	//$obj->heinv_pricenett = $obj->heinv_price ;


	$obj->heinv_isdisabled = $rs->fields['heinv_isdisabled'];
	$obj->heinv_createby = $rs->fields['heinv_createby'];
	$obj->heinv_createdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heinv_createdate']));
	$obj->heinv_modifyby = $rs->fields['heinv_modifyby'];
	$obj->heinv_modifydate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heinv_modifydate']));
	$obj->heinvctg_id = $rs->fields['heinvctg_id'];
	$obj->region_id = $rs->fields['region_id'];	
	$obj->season_id = $rs->fields['season_id'];
	
	$sql = "SELECT * FROM master_heinvctg WHERE heinvctg_id='".$obj->heinvctg_id."' AND region_id='".$obj->region_id."' ";
	$rsI = $conn->Execute($sql);
	$obj->heinvctg_name = $rsI->fields['heinvctg_name'];
	$obj->heinvctg_sizetag = $rsI->fields['heinvctg_sizetag'];

		
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
