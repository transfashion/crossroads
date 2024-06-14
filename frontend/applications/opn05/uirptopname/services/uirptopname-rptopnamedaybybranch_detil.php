<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$day 		= $_POST['day'];
	$criteria	= $_POST['criteria'];
 
 
	
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		$opnameproject_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_iteminventory_id', '', "{criteria_value}");
		$coverage  = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_coverage',  '', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		//$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		//$dateend   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
		
	}	
	
	
	$data = array();

	if ($coverage=='REGION') {
		$sql = "EXEC opn05_RptSummaryOpnameday_SelectAllBranch '$day', '$opnameproject_id', '$branch_id', '$region_id'";
	} else {
		$sql = "EXEC opn05_RptSummaryOpnameday_SelectMultiByBranch '$day', '$opnameproject_id' , '$branch_id', '$region_id'";
	}
	
	
	
	$rs = $conn->Execute($sql);
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->day				= $rs->fields['day'];
		$obj->branch_id			= $rs->fields['branch_id'];
		$obj->branch_name		= $rs->fields['branch_name'];
		$obj->qty				= 1*$rs->fields['qty'];
		
		$obj->qty_1				= 1*$rs->fields['1_qty'];
		$obj->qty_2				= 1*$rs->fields['2_qty'];
		$obj->qty_3				= 1*$rs->fields['3_qty'];
		$obj->qty_4				= 1*$rs->fields['4_qty'];
		$obj->qty_5				= 1*$rs->fields['5_qty'];
		$obj->qty_6				= 1*$rs->fields['6_qty'];
		$obj->qty_7				= 1*$rs->fields['7_qty'];
		$obj->qty_8				= 1*$rs->fields['8_qty'];
		$obj->qty_9				= 1*$rs->fields['9_qty'];
		$obj->qty_10			= 1*$rs->fields['10_qty'];
		$obj->qty_11			= 1*$rs->fields['11_qty'];
		$obj->qty_12			= 1*$rs->fields['12_qty'];
		$obj->qty_13			= 1*$rs->fields['13_qty'];
		$obj->qty_14			= 1*$rs->fields['14_qty'];
		$obj->qty_15			= 1*$rs->fields['15_qty'];
		$obj->qty_16			= 1*$rs->fields['16_qty'];
		$obj->qty_17			= 1*$rs->fields['17_qty'];
		$obj->qty_18			= 1*$rs->fields['18_qty'];
		$obj->qty_19			= 1*$rs->fields['19_qty'];
		$obj->qty_20			= 1*$rs->fields['20_qty'];
		$obj->qty_21			= 1*$rs->fields['21_qty'];
		$obj->qty_22			= 1*$rs->fields['22_qty'];
		$obj->qty_23			= 1*$rs->fields['23_qty'];
		$obj->qty_24			= 1*$rs->fields['24_qty'];
		$obj->qty_25			= 1*$rs->fields['25_qty'];
		$obj->qty_26			= 1*$rs->fields['26_qty'];
		$obj->qty_27			= 1*$rs->fields['27_qty'];
		$obj->qty_28			= 1*$rs->fields['28_qty'];
		$obj->qty_29			= 1*$rs->fields['29_qty'];
		$obj->qty_30			= 1*$rs->fields['30_qty'];
		$obj->qty_31			= 1*$rs->fields['31_qty'];
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