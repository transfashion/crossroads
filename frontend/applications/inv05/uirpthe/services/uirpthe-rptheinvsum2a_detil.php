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
 
	//A52332F2-51A9-485D-8D81-C2BDD3A7ACB4|10|32|0
	$args = explode("|", $ids);
	$cacheid = $args[0];
	$branch_id = $args[1];
 

	$sql = "
		SET NOCOUNT ON
		select  branch_id, heinvgro_id, heinvgro_name,heinvctg_id,heinvctg_namegroup ,[END] = SUM(cast([END] as INT)) from dbo.cache_heinvsummary 
		WHERE cacheid='$cacheid'  and branch_id ='$branch_id' AND REPSECTION='ITEM'
		GROUP by region_id,branch_id, heinvgro_id, heinvgro_name,heinvctg_id,heinvctg_namegroup  
		ORDER BY  heinvctg_namegroup   ";
	
	$rs = $conn->Execute($sql);
 
	
	
	$data = array();
	while (!$rs->EOF) {
		unset($obj);
 
		$obj->branch_id = $rs->fields['branch_id']; 
		
			$sql_branch ="select branch_name FROM master_branch WHERE branch_id = '$branch_id'";
			$rsBranch = $conn->Execute($sql_branch);
		
		$obj->branch_name = $rsBranch->fields['branch_name']; 
		$obj->heinvgro_id = $rs->fields['heinvgro_id']; 
		$obj->heinvgro_name = $rs->fields['heinvgro_name']; 
		$obj->heinvctg_id = $rs->fields['heinvctg_id']; 
		$obj->heinvctg_namegroup = $rs->fields['heinvctg_namegroup']; 
 
		$obj->END = $rs->fields['END']; 
		
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