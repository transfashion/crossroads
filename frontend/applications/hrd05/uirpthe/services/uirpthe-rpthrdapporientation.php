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
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		$contractno = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_contractno', 'employee_contractno', "{criteria_value}");
		$tipekontrak = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_tipekontrak', 'employee_tipekontrak', "{criteria_value}");
		$tanggalkontrak = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_kontrak', 'employee_kontrak', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_startdate', 'employee_startdate', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_enddate', 'employee_enddate', "{criteria_value}");
		$pihak2name = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_pihak2name', 'employee_pihak2name', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_region_id', 'region_id', "{criteria_value}");
		}


	$data = array();
	unset($obj);
	$obj->ids = "$contractno|$tipekontrak|$tanggalkontrak|$startdate|$enddate|$pihak2name";
	$data[] = $obj;
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>