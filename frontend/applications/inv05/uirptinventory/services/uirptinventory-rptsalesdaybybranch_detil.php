<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$day 		= $_POST['day'];
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
		$sql = "EXEC inv05_RptSummarySalesday_SelectAllBranch '$day', '$datestart', '$dateend', '$branch_id', '$region_id', '$includeconsumable'";
	} else {
		$sql = "EXEC inv05_RptSummarySalesday_SelectMultiByBranch '$day', '$datestart', '$dateend', '$branch_id', '$region_id', '$includeconsumable'";
	}
	

	
	
	$rs = $conn->Execute($sql);
	while (!$rs->EOF) {
		unset($obj);
		$obj->day				= $rs->fields['day'];
		$obj->branch_id			= $rs->fields['branch_id'];
		$obj->branch_name		= $rs->fields['branch_name'];
		$obj->qty				= 1*$rs->fields['qty'];
		$obj->subtotal			= 1*$rs->fields['subtotal'];
		
		$obj->qty_1				= 1*$rs->fields['1_qty'];
		$obj->sub_1				= 1*$rs->fields['1_subtotal'];
		$obj->com_1				= 1*$rs->fields['1_iscompleted'];
		
		$obj->qty_2				= 1*$rs->fields['2_qty'];
		$obj->sub_2				= 1*$rs->fields['2_subtotal'];
		$obj->com_2				= 1*$rs->fields['2_iscompleted'];

		$obj->qty_3				= 1*$rs->fields['3_qty'];
		$obj->sub_3				= 1*$rs->fields['3_subtotal'];
		$obj->com_3				= 1*$rs->fields['3_iscompleted'];

		$obj->qty_4				= 1*$rs->fields['4_qty'];
		$obj->sub_4				= 1*$rs->fields['4_subtotal'];
		$obj->com_4				= 1*$rs->fields['4_iscompleted'];

		$obj->qty_5				= 1*$rs->fields['5_qty'];
		$obj->sub_5				= 1*$rs->fields['5_subtotal'];
		$obj->com_5				= 1*$rs->fields['5_iscompleted'];

		$obj->qty_6				= 1*$rs->fields['6_qty'];
		$obj->sub_6				= 1*$rs->fields['6_subtotal'];
		$obj->com_6				= 1*$rs->fields['6_iscompleted'];

		$obj->qty_7				= 1*$rs->fields['7_qty'];
		$obj->sub_7				= 1*$rs->fields['7_subtotal'];
		$obj->com_7				= 1*$rs->fields['7_iscompleted'];

		$obj->qty_8				= 1*$rs->fields['8_qty'];
		$obj->sub_8				= 1*$rs->fields['8_subtotal'];
		$obj->com_8				= 1*$rs->fields['8_iscompleted'];

		$obj->qty_9				= 1*$rs->fields['9_qty'];
		$obj->sub_9				= 1*$rs->fields['9_subtotal'];
		$obj->com_9				= 1*$rs->fields['9_iscompleted'];

		$obj->qty_10			= 1*$rs->fields['10_qty'];
		$obj->sub_10			= 1*$rs->fields['10_subtotal'];
		$obj->com_10			= 1*$rs->fields['10_iscompleted'];

		$obj->qty_11			= 1*$rs->fields['11_qty'];
		$obj->sub_11			= 1*$rs->fields['11_subtotal'];
		$obj->com_11			= 1*$rs->fields['11_iscompleted'];

		$obj->qty_12			= 1*$rs->fields['12_qty'];
		$obj->sub_12			= 1*$rs->fields['12_subtotal'];
		$obj->com_12			= 1*$rs->fields['12_iscompleted'];

		$obj->qty_13			= 1*$rs->fields['13_qty'];
		$obj->sub_13			= 1*$rs->fields['13_subtotal'];
		$obj->com_13			= 1*$rs->fields['13_iscompleted'];

		$obj->qty_14			= 1*$rs->fields['14_qty'];
		$obj->sub_14			= 1*$rs->fields['14_subtotal'];
		$obj->com_14			= 1*$rs->fields['14_iscompleted'];

		$obj->qty_15			= 1*$rs->fields['15_qty'];
		$obj->sub_15			= 1*$rs->fields['15_subtotal'];
		$obj->com_15			= 1*$rs->fields['15_iscompleted'];

		$obj->qty_16			= 1*$rs->fields['16_qty'];
		$obj->sub_16			= 1*$rs->fields['16_subtotal'];
		$obj->com_16			= 1*$rs->fields['16_iscompleted'];

		$obj->qty_17			= 1*$rs->fields['17_qty'];
		$obj->sub_17			= 1*$rs->fields['17_subtotal'];
		$obj->com_17			= 1*$rs->fields['17_iscompleted'];

		$obj->qty_18			= 1*$rs->fields['18_qty'];
		$obj->sub_18			= 1*$rs->fields['18_subtotal'];
		$obj->com_18			= 1*$rs->fields['18_iscompleted'];

		$obj->qty_19			= 1*$rs->fields['19_qty'];
		$obj->sub_19			= 1*$rs->fields['19_subtotal'];
		$obj->com_19			= 1*$rs->fields['19_iscompleted'];

		$obj->qty_20			= 1*$rs->fields['20_qty'];
		$obj->sub_20			= 1*$rs->fields['20_subtotal'];
		$obj->com_20			= 1*$rs->fields['20_iscompleted'];

		$obj->qty_21			= 1*$rs->fields['21_qty'];
		$obj->sub_21			= 1*$rs->fields['21_subtotal'];
		$obj->com_21			= 1*$rs->fields['21_iscompleted'];

		$obj->qty_22			= 1*$rs->fields['22_qty'];
		$obj->sub_22			= 1*$rs->fields['22_subtotal'];
		$obj->com_22			= 1*$rs->fields['22_iscompleted'];

		$obj->qty_23			= 1*$rs->fields['23_qty'];
		$obj->sub_23			= 1*$rs->fields['23_subtotal'];
		$obj->com_23			= 1*$rs->fields['23_iscompleted'];

		$obj->qty_24			= 1*$rs->fields['24_qty'];
		$obj->sub_24			= 1*$rs->fields['24_subtotal'];
		$obj->com_24			= 1*$rs->fields['24_iscompleted'];

		$obj->qty_25			= 1*$rs->fields['25_qty'];
		$obj->sub_25			= 1*$rs->fields['25_subtotal'];
		$obj->com_25			= 1*$rs->fields['25_iscompleted'];

		$obj->qty_26			= 1*$rs->fields['26_qty'];
		$obj->sub_26			= 1*$rs->fields['26_subtotal'];
		$obj->com_26			= 1*$rs->fields['26_iscompleted'];

		$obj->qty_27			= 1*$rs->fields['27_qty'];
		$obj->sub_27			= 1*$rs->fields['27_subtotal'];
		$obj->com_27			= 1*$rs->fields['27_iscompleted'];

		$obj->qty_28			= 1*$rs->fields['28_qty'];
		$obj->sub_28			= 1*$rs->fields['28_subtotal'];
		$obj->com_28			= 1*$rs->fields['28_iscompleted'];

		$obj->qty_29			= 1*$rs->fields['29_qty'];
		$obj->sub_29			= 1*$rs->fields['29_subtotal'];
		$obj->com_29			= 1*$rs->fields['29_iscompleted'];

		$obj->qty_30			= 1*$rs->fields['30_qty'];
		$obj->sub_30			= 1*$rs->fields['30_subtotal'];
		$obj->com_30			= 1*$rs->fields['30_iscompleted'];

		$obj->qty_31			= 1*$rs->fields['31_qty'];
		$obj->sub_31			= 1*$rs->fields['31_subtotal'];
		$obj->com_31			= 1*$rs->fields['31_iscompleted'];

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