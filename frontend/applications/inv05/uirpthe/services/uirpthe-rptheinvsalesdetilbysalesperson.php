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

		$bon_region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");


	
			
	}
	

	
	
	$data = array();
	unset($obj);
	$obj->ids = "$region_id|$startdate|$enddate";
    $obj->name = "";
	$data[] = $obj;
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>