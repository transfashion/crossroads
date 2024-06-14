<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];


//$__POSTDATA = json_decode(stripslashes($__JSONDATA));
//print $criteria;


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
	}
	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_bon_id', 'bon_id', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id', 'branch_id', " %s = '%s' ");	
	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_bon_isvoid', 'bon_isvoid', " %s = '%s' ");		

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_bon_datestart', 'bon_date', " convert(varchar(10),bon_date,120)>=convert(varchar(10),'{criteria_value}',120) ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_bon_dateend', 'bon_date', " convert(varchar(10),bon_date,120)<=convert(varchar(10),'{criteria_value}',120) ");
	

}



//print $SQL_CRITERIA;
if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_hepos WHERE $SQL_CRITERIA ORDER BY bon_date, bon_id";
} else {
	$sql = "SELECT * FROM transaksi_hepos ORDER BY bon_date, bon_id";
}



if ($limit) {
	$rs = $conn->Execute($sql);
	$totalCount = $rs->recordCount();
	$rs = $conn->SelectLimit($sql, $limit, $start);
} else {
	$rs = $conn->Execute($sql);
	$totalCount = $rs->recordCount();
}


$data = array();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->bon_id = $rs->fields['bon_id'];
	$obj->bon_idext = $rs->fields['bon_idext'];
	$obj->bon_event = $rs->fields['bon_event'];
	$obj->bon_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['bon_date'])); 
	$obj->bon_createby = $rs->fields['bon_createby'];
	$obj->bon_isvoid = $rs->fields['bon_isvoid'];
	$obj->bon_msubtotal = (float) $rs->fields['bon_msubtotal'];
	$obj->bon_msubtracttotal = (float) $rs->fields['bon_msubtracttotal'];
	$obj->bon_msubtotaltobedisc = (float) $rs->fields['bon_msubtotaltobedisc'];
	$obj->bon_mdiscpaympercent = (float) $rs->fields['bon_mdiscpaympercent'];
	$obj->bon_mtotal = (float) $rs->fields['bon_mtotal'];
	$obj->bon_msaletax = (float) $rs->fields['bon_msaletax'];
	$obj->bon_msalenet = (float) $rs->fields['bon_msalenet'];
	$obj->bon_itemqty = (int) $rs->fields['bon_itemqty'];
	
	$obj->region_id = $rs->fields['region_id'];
	$obj->branch_id = $rs->fields['branch_id'];
		
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