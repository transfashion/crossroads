<?
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   igunrn    created date 08/11/2011 13:21
Opname Project
Filename: uitrnopnameproject-gridlistload.php
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

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_opnameproject_id', 'opnameproject_id', "refParser");	
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_isPosted', 'opnameproject_isposted', " %s = '%s' ");
				 

	
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_opnameproject WHERE $SQL_CRITERIA ORDER BY opnameproject_id DESC";
} else {
	$sql = "SELECT * FROM transaksi_opnameproject ORDER BY opnameproject_id DESC";
}

 

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {

	unset($obj);
	$obj->opnameproject_id = trim($rs->fields['opnameproject_id']);
	$obj->opnameproject_startdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['opnameproject_startdate']));
	$obj->opnameproject_enddate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['opnameproject_enddate']));
	$obj->opnameproject_isdisabled = trim($rs->fields['opnameproject_isdisabled']);
	$obj->opnameproject_descr = trim($rs->fields['opnameproject_descr']);
	$obj->opnameproject_createby = trim($rs->fields['opnameproject_createby']);
	$obj->opnameproject_createdate = trim($rs->fields['opnameproject_createdate']);
	$obj->opnameproject_modifyby = trim($rs->fields['opnameproject_modifyby']);
	$obj->opnameproject_modifydate = trim($rs->fields['opnameproject_modifydate']);
	$obj->rowid = trim($rs->fields['rowid']);
	$obj->opnameproject_isposted = trim($rs->fields['opnameproject_isposted']);
	$obj->opnameproject_isgenerated = trim($rs->fields['opnameproject_isgenerated']);
	$obj->region_id = trim($rs->fields['region_id']);
	
	$region_id = trim($rs->fields['region_id']);
	$SQLR = "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
	$rsR = $conn->execute($SQLR);
	$obj->region_name = trim($rsR->fields['region_name']);
	

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