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
		$type = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_type', '', "{criteria_value}");
		$date = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_date', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");		
					
	}
	
	$data = array();
	
 	$d = explode("-", $datestart);
	$day1 = $d[2];
	$d = explode("-", $dateend);
	$day3 = $d[2];
	
	$d = explode("-", $date);
	$day2 = $d[2];
	if ($type == "Daily")
	{
		for ($i=1; $i<=$day2; $i++) {
		unset($obj);
		$obj->ids = $i;
		$obj->name = "test";
		$data[] = $obj;
		}
	}
	if ($type == "Monthly")
	{
 		unset($obj);
		$obj->ids = 1;
		$obj->name = "test";
		$data[] = $obj;
 	}
	if ($type == "Weekly")
	{
 		for ($i=$day1; $i<=$day3; $i++) {
		unset($obj);
		$obj->ids = $i;
		$obj->name = "test";
		$data[] = $obj;
		}
 	}
/**
*   $data = array();
* 	unset($obj);
* 	$obj->ids = "$username|$limit|$start|$criteria|$ids|$tgl";
* 	$data[] = $obj;
* 	
* 	$objResult = new WebResultObject("objResult");
* 	
* 	$objResult->success = true;
* 	$objResult->data = $data;
* 	unset($objResult->errors);
*/

 	/**
* $obj->ids = "$username|$limit|$start|$criteria|$ids|$tgl";
*  	$obj->name = "test";
*  	$data[] = $obj;
*/

	
		
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>