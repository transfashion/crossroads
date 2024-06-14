<?php

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
	
		$region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$year_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'cmbYear', '', "{criteria_value}");
		$month_id     = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'cmbMonth',   '', "{criteria_value}");
		$mode_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_mode_id', '', "{criteria_value}");
		
}

 
 
 
    $args = explode("|", $ids);
	$cacheid = $args[0];
	$page    = $args[1];
	$limit	 = $args[2];
	$start   = $args[3];
 $obj->ids = "$cacheid|$jumlah_halaman|$limit|$start";
 
 print $cacheid;
 die();
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 

 

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data =  $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));

  


?> 