<?

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];
$ids 		= $_POST['ids'];

$param = "";
$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));

if (is_array($objCriteria)) {
	$CRITERIA_DB = array();
	while (list($name, $value) = each($objCriteria)) {
		$CRITERIA_DB[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	//SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_opnameproject_id', 'opnameproject_id', " %s = '%s' ");
	$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
	$opnameproject_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_opnameproject_id', '', "{criteria_value}");


}

$data = array(); 

$sql = "EXEC E_FRM2_MGP_OPNAME.dbo.op_RptOpnameDaily '$ids','|','$datestart','$opnameproject_id'";
//$sql = "SELECT * FROM E_FRM2_MGP_OPNAME.dbo.master_dailyopname";

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();

while (!$rs->EOF) {
	unset($obj);
/*
	$iteminventory_id = strtoupper($rs->fields['iteminventory_id']);
	$obj->iteminventory_id = strtoupper($rs->fields['iteminventory_id']);
	
	$obj->iteminventory_name 	= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_name']);
	$obj->iteminventory_color 	= strtoupper(trim($rs->fields['iteminventory_color']));
	$obj->iteminventory_size 	= strtoupper(trim($rs->fields['iteminventory_size']));
	$obj->branch_id 			= trim($rs->fields['branch_id']);
	$obj->branch_name 			= trim($rs->fields['branch_name']);
	$obj->BEGIN 				= 1*$rs->fields['BEGIN'];
	$obj->OPNAME 				= 1*$rs->fields['OPNAME'];
	$obj->END 					= 1*$rs->fields['END'];
	$data[] = $obj;
*/


	$obj->iteminventory_id = strtoupper($rs->fields['iteminventory_id']);
	
	$obj->iteminventory_name 	= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_name']);
	$obj->iteminventory_color 	= strtoupper(trim($rs->fields['iteminventory_color']));
	$obj->iteminventory_size 	= strtoupper(trim($rs->fields['iteminventory_size']));
	$obj->iteminventory_group 	= strtoupper(trim($rs->fields['iteminventorygroup_id']));
	$obj->iteminventory_subgroup 	= strtoupper(trim($rs->fields['iteminventorysubgroup_id']));
	$obj->branch_id 			= trim($rs->fields['branch_id']);
	$obj->branch_name 			= trim($rs->fields['branch_name']);
	$obj->BEGIN 				= 1*$rs->fields['BEGIN'];
	$obj->OPNAME 				= 1*$rs->fields['OPNAME'];
	$obj->END 					= 1*$rs->fields['END'];
	
	
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