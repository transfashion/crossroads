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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemovingtype_id', 'hemovingtype_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_id', 'hemoving_id', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_descr', 'hemoving_descr', " {db_field} LIKE '%{criteria_value}%' ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id_from', 'branch_id_fr', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id_to', 'branch_id_to', " %s = '%s' ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_isprop', 'hemoving_isprop', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_isproplock', 'hemoving_isproplock', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_issend', 'hemoving_issend', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_isrecv', 'hemoving_isrecv', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_ispost', 'hemoving_ispost', " %s = '%s' ");


	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_datestart', 'hemoving_date_fr', " convert(varchar(10),hemoving_date_fr,120)>=convert(varchar(10),'{criteria_value}',120) ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_dateend', 'hemoving_date_fr', " convert(varchar(10),hemoving_date_fr,120)<=convert(varchar(10),'{criteria_value}',120) ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_ref_id', 'ref_id', " %s = '%s' ");

	
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_hemoving WHERE $SQL_CRITERIA ORDER BY hemoving_id DESC";
} else {
	$sql = "SELECT * FROM transaksi_hemoving ORDER BY hemoving_id DESC";
}




$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);

$data = array();
$i=0;
while (!$rs->EOF) {
       $i = $i+1;
	unset($obj);
	$obj->hemoving_id = $rs->fields['hemoving_id'];
	$obj->hemoving_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['hemoving_date']));
	$obj->hemoving_date_fr = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['hemoving_date_fr']));
	$obj->hemoving_date_to = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['hemoving_date_to']));
	$obj->hemoving_isprop = $rs->fields['hemoving_isprop'];
	$obj->hemoving_issend = $rs->fields['hemoving_issend'];
	$obj->hemoving_isrecv = $rs->fields['hemoving_isrecv'];
	$obj->hemoving_ispost = $rs->fields['hemoving_ispost'];
	$obj->hemoving_descr = str_replace(array('"', "'"), array("", ""), $rs->fields['hemoving_descr']);
	$obj->hemoving_createby = $rs->fields['hemoving_createby'];
	$obj->hemoving_createdate = $rs->fields['hemoving_createdate'];
	$obj->hemovingtype_id = $rs->fields['hemovingtype_id'];
	$obj->region_id = $rs->fields['region_id'];
	$obj->region_id_out = $rs->fields['region_id_out'];
	
	
	$sql = "select branch_name from master_branch where branch_id='".$rs->fields['branch_id_fr']."'";
	$rsI = $conn->Execute($sql);
	$obj->branch_id_fr = $rsI->fields['branch_name'];
	$sql = "select branch_name from master_branch where branch_id='".$rs->fields['branch_id_to']."'";
	$rsI = $conn->Execute($sql);	
	$obj->branch_id_to = $rsI->fields['branch_name'];
	

	if ($rs->fields['hemovingtype_id']=='RV' or $rs->fields['hemovingtype_id']=='DO') {
		$sql = "select rekanan_name from master_rekanan where rekanan_id='".$rs->fields['rekanan_id']."'";	
		$rsI = $conn->Execute($sql);
		$obj->rekanan_id = $rsI->fields['rekanan_name'];
	} else {
		$obj->rekanan_id = $rs->fields['rekanan_id'];
	}
	
	
	$obj->currency_id = $rs->fields['currency_id'];
	$obj->ref_id = $rs->fields['ref_id'];

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
