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

	
 //Default Criteria
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_channel_id', 'channel_id', " %s = '%s' ");


	

//	 User Criteria 
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_season', 'season_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_article', 'iteminventory_article', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_size', 'iteminventory_size', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_color', 'iteminventory_color', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_iteminventory_id', 'iteminventory_id', "refParser");
//	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymoving_descr', 'inventorymoving_descr', " %s LIKE '%s' ");
//	SQLUTIL::BuildCriteriaDate(&$SQL_CRITERIA, $criteria, 'obj_search_chk_inventorymoving_datestart', 'obj_search_chk_inventorymoving_dateend', 'inventorymoving_date');

 
}





if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_iteminventory WHERE $SQL_CRITERIA ORDER BY iteminventory_id DESC";
} else {
	$sql = "SELECT * FROM master_iteminventory ORDER BY iteminventory_id DESC";
}

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->iteminventory_id 					= $rs->fields['iteminventory_id'];
	$obj->iteminventory_factorycode 		= $rs->fields['iteminventory_factorycode'];
	$obj->iteminventory_article 			= $rs->fields['iteminventory_article'];
	$obj->iteminventory_material 			= $rs->fields['iteminventory_material'];
	$obj->iteminventory_color 				= $rs->fields['iteminventory_color'];
	$obj->iteminventory_size 				= $rs->fields['iteminventory_size'];
	$obj->iteminventory_descr 				= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_descr']);
	$obj->iteminventory_isdisabled 			= $rs->fields['iteminventory_isdisabled'];
	$obj->region_id 						= $rs->fields['region_id'];				
	$obj->season_id 						= $rs->fields['season_id'];								
			
			
					
	/* 
	$obj->inventorymoving_id = $rs->fields['inventorymoving_id'];
	$obj->inventorymoving_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['inventorymoving_date']));
	$obj->inventorymoving_descr = str_replace(array("'", '"'), array("", ""), $rs->fields['inventorymoving_descr']);
	$obj->region_id_source = $rs->fields['region_id_source'];
	
	$obj->inventorymoving_isproposed 	= $rs->fields['inventorymoving_isproposed'];
	$obj->inventorymoving_issent 		= $rs->fields['inventorymoving_ispostedsend'];
	$obj->inventorymoving_isreceived 	= $rs->fields['inventorymoving_ispostedreceive'];
	$obj->inventorymoving_isposted		= $rs->fields['inventorymoving_isposted'];

	
	//From dan To 
	$branch_id_from	= $rs->fields['branch_id_source'];
	$branch_id_to	= $rs->fields['branch_id_target'];
	
	$sql = "select branch_name from master_branch where branch_id='$branch_id_from'";
	$rsI = $conn->Execute($sql);
	$obj->branch_id_from 	= $rsI->fields['branch_name'];
	*/
	$region_id	= $rs->fields['region_id'];
	$sql = "select region_name from master_region where region_id='$region_id'";
	$rsI = $conn->Execute($sql);
	$obj->region_id 	= $rsI->fields['region_name'];
	
	
 

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
