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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymovingtype_id', 'inventorymovingtype_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_iteminventorytype_id', 'iteminventorytype_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_iteminventorysubtype_id', 'iteminventorysubtype_id', " %s = '%s' ");

	/* User Criteria */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymoving_id', 'inventorymoving_id', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymoving_descr', 'inventorymoving_descr', " %s LIKE '%s' ");
	SQLUTIL::BuildCriteriaDate(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymoving_datestart', 'obj_search_chk_inventorymoving_dateend', 'inventorymoving_date');
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id_source', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id_from', 'branch_id_source', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id_to', 'branch_id_target', " %s = '%s' ");


	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymoving_status_sent', 'inventorymoving_ispostedsend', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymoving_status_receive', 'inventorymoving_ispostedreceive', " %s = '%s' ");

	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'FORM_SEND', '', "( inventorymoving_source='TR_Send' OR inventorymoving_isproposed=1 )");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'FORM_RECEIVE', '', "( inventorymoving_source='TR_Receive' OR inventorymoving_ispostedsend=1 )");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'FORM_RECEIVEUNPOST', '', "( inventorymoving_ispostedreceive=1 AND inventorymoving_isposted=0 )");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'FORM_COST', '', " inventorymoving_ispostedreceive=1 ");
	
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_inventorymoving WHERE $SQL_CRITERIA ORDER BY inventorymoving_date DESC";
} else {
	$sql = "SELECT * FROM transaksi_inventorymoving ORDER BY inventorymoving_date DESC";
}

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {

	unset($obj);
	$obj->inventorymoving_id = $rs->fields['inventorymoving_id'];
	$obj->inventorymoving_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['inventorymoving_date']));
	$obj->inventorymoving_descr = str_replace(array("'", '"'), array("", ""), $rs->fields['inventorymoving_descr']);
	$obj->region_id_source = $rs->fields['region_id_source'];
	
	$obj->inventorymoving_isproposed 	= $rs->fields['inventorymoving_isproposed'];
	$obj->inventorymoving_issent 		= $rs->fields['inventorymoving_ispostedsend'];
	$obj->inventorymoving_isreceived 	= $rs->fields['inventorymoving_ispostedreceive'];
	$obj->inventorymoving_isposted		= $rs->fields['inventorymoving_isposted'];

	/* From dan To */
	$branch_id_from	= $rs->fields['branch_id_source'];
	$branch_id_to	= $rs->fields['branch_id_target'];
	
	$sql = "select branch_name from master_branch where branch_id='$branch_id_from'";
	$rsI = $conn->Execute($sql);
	$obj->branch_id_from 	= $rsI->fields['branch_name'];
	
	$sql = "select branch_name from master_branch where branch_id='$branch_id_to'";
	$rsI = $conn->Execute($sql);
	$obj->branch_id_to 	= $rsI->fields['branch_name'];
	

	if ($rs->fields['inventorymoving_source']=='TR_Send' && $obj->inventorymoving_issent==1) {
		$obj->inventorymoving_isproposed = 1;
	}

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