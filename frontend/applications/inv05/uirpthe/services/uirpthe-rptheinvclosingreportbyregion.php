<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];


	$param = "";
	$SQL_CRITERIA = "";
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$periode_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_periode', '', "{criteria_value}");

		//$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		//$datestart_new = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart_new', '', "{criteria_value}");
		//$dateend_new   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend_new',   '', "{criteria_value}");
		//$datestart_old = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart_old', '', "{criteria_value}");
		//$dateend_old   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend_old',   '', "{criteria_value}");
	
			
	}
	

	$SQLC = "SELECT heinvctg_id,heinvctg_name FROM master_heinvctg WHERE region_id = '$region_id'";
	$rsC = $conn->execute($SQLC);
	
	
		$data = array();
		WHILE (!$rsC->EOF) {
		 
			unset($obj);
			$heinvctg_id  = $rsC->fields['heinvctg_id'];
			$heinvctg_name  = $rsC->fields['heinvctg_name'];
			
			$obj->ids = "$region_id|$periode_id|$heinvctg_id|$heinvctg_name";

			$data[] = $obj;	 
		 
		 
		 	$rsC->MoveNext();
		 }
	
	
	
	
	
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>