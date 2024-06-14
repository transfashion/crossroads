<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$ids 		= $_POST['ids'];
	$criteria	= $_POST['criteria'];
	$includeconsumable = $_POST['includeconsumable']=='True' ? 1 : 0;
		
	
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		
		$coverage  = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_coverage',  '', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");

		
	}	
	
	
	$data = array();
	if ($coverage=='REGION') {
		$sql = "EXEC inv05_RptSummaryInvOPNAMEPerItem_SelectAllBranch '$ids', '|', '$datestart', '$dateend', '$includeconsumable'";
	} else {
		$sql = "EXEC inv05_RptSummaryInvOPNAMEPerItem_SelectMultiByBranch '$ids', '|', '$datestart', '$dateend', '$branch_id', '$includeconsumable'";
	}
	
	$rs = $conn->Execute($sql);
	while (!$rs->EOF) {
		unset($obj);
		$iteminventory_id = strtoupper($rs->fields['iteminventory_id']);
		$obj->iteminventory_id = strtoupper($rs->fields['iteminventory_id']);
		$obj->iteminventory_name = str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_name']);	
		$obj->iteminventory_article = $rs->fields['iteminventory_article'];		
		$obj->iteminventory_material = $rs->fields['iteminventory_material'];
		$obj->iteminventory_color = $rs->fields['iteminventory_color'];
		$obj->iteminventory_size = $rs->fields['iteminventory_size'];
		$obj->group_name = $rs->fields['group_name'];
		$obj->subgroup_name = $rs->fields['subgroup_name'];
		$obj->color_name = $rs->fields['color_name'];
		$obj->size_name = $rs->fields['size_name'];
		
		$obj->BEGIN = 1*$rs->fields['BEGIN'];
		$obj->RV = 1*$rs->fields['RV'];
		$obj->TRIN = 1*$rs->fields['TRIN'];
		$obj->TROUT = 1*$rs->fields['TROUT'];
		$obj->ASMIN = 1*$rs->fields['ASMIN'];
		$obj->ASMOUT = 1*$rs->fields['ASMOUT'];
		$obj->ADJ = 1*$rs->fields['ADJ'];
		$obj->OTHER = 1*$rs->fields['OTHER'];
		$obj->SALES = 1*$rs->fields['SALES'];

	
		$data[] = $obj;
		$rs->MoveNext();
	}

	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = count($data);
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>