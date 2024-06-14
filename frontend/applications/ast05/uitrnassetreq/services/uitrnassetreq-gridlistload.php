<?
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   luki.widodorn    created date 26/10/2011 10:14
Asset Request
Filename: uitrnAssetReq-gridlistload.php
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
	
	//SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_channel_id', 'channel_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id', 'branch_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_project_id', 'project_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_strukturunit_id', 'strukturunit_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_owner_strukturunit_id', 'owner_strukturunit_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_assetrequest_id', 'assetrequest_id', "{db_field} LIKE '%{criteria_value}%'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_assetrequest_isposted', 'assetrequest_isposted', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_assetrequest_issent', 'assetrequest_issent', " %s = '%s' ");
	
}
 

if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_assetrequest WHERE $SQL_CRITERIA ORDER BY assetrequest_id DESC";
} else {
	$sql = "SELECT * FROM transaksi_assetrequest ORDER BY assetrequest_id DESC";
}



$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();



while (!$rs->EOF) {

	unset($obj);
	$obj->assetrequest_id = trim($rs->fields['assetrequest_id']);
	$obj->assetrequest_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['assetrequest_date']));
	$obj->assetrequest_isdisabled = trim($rs->fields['assetrequest_isdisabled']);
	$obj->assetrequest_isposted = 1*trim($rs->fields['assetrequest_isposted']);
	$obj->assetrequest_issent = 1*trim($rs->fields['assetrequest_issent']);
	$obj->assetrequest_duedate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['assetrequest_duedate']));
	$obj->assetrequest_descr = trim($rs->fields['assetrequest_descr']);
	$obj->assetrequest_dept = trim($rs->fields['assetrequest_dept']);
	$obj->assetrequest_createby = trim($rs->fields['assetrequest_createby']);
	$obj->assetrequest_createdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['assetrequest_createdate']));
	$obj->assetrequest_modifyby = trim($rs->fields['assetrequest_modifyby']);
	$obj->assetrequest_modifydate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['assetrequest_modifydate']));

	
	$region_id = trim($rs->fields['region_id']);
		$SQLB					= 	"SELECT region_name FROM master_region WHERE region_id = '$region_id'";
	    $rsB 					= 	$conn->execute($SQLB);
		$region_name 			= 	trim($rsB->fields['region_name']);
		$obj->region_id			=	$region_id;
		$obj->region_name		=	$region_name;


	$branch_id = trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		
	$strukturunit_id = trim($rs->fields['strukturunit_id']);
		$SQLD					= 	"SELECT strukturunit_name FROM master_strukturunit WHERE strukturunit_id = '$strukturunit_id'";
	    $rsD 					= 	$conn->execute($SQLD);
		$strukturunit_name 			= 	trim($rsD->fields['strukturunit_name']);
		$obj->strukturunit_id			=	$strukturunit_id;
		$obj->strukturunit_name			=	$strukturunit_name;
		
	//$obj->owner_strukturunit_id = trim($rs->fields['owner_strukturunit_id']);
	
	$owner_strukturunit_id = trim($rs->fields['owner_strukturunit_id']);
		$SQLF					= 	"SELECT strukturunit_name FROM master_strukturunit WHERE strukturunit_id = '$owner_strukturunit_id'";
	    $rsF 					= 	$conn->execute($SQLF);
		$owner_strukturunit_name 			= 	trim($rsF->fields['strukturunit_name']);
		$obj->owner_strukturunit_id			=	$owner_strukturunit_id;
		$obj->owner_strukturunit_name			=	$owner_strukturunit_name;
	
	//$obj->project_id = trim($rs->fields['project_id']);
	
	$project_id = trim($rs->fields['project_id']);
		$SQLE					= 	"SELECT project_name FROM transaksi_project WHERE project_id = '$project_id'";
	    $rsE 					= 	$conn->execute($SQLE);
		$project_name 			= 	trim($rsE->fields['project_name']);
		$obj->project_id			=	$project_id;
		$obj->project_name			=	$project_name;
	
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