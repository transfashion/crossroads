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
	$page    = $args[1];
	$limit	 = $args[2];
	$start   = $args[3];


	$sql = "
		select * from dbo.cache_hepossummarybypayment1 
		WHERE cacheid='$cacheid' ORDER BY id, seq";
	//$rs = $conn->Execute($sql);
	//$totalCount = $rs->recordCount();
	$rs = $conn->SelectLimit($sql, $limit, $start);
	$data = array();
	
	$alt = 1;
	$lastid = "";
	while (!$rs->EOF) {
		unset($obj);

		$obj->bon_paymentcount = $rs->fields['bon_paymentcount'];
		$obj->bon_discpaympercent = (int) $rs->fields['bon_discpaympercent'];
		$obj->bon_id = $rs->fields['bon_id'];
		$obj->bon_date = $rs->fields['bon_date'];
		$obj->salesperson_name = $rs->fields['salesperson_name'];
		$obj->pospayment_name = $rs->fields['pospayment_name'];
        $obj->voucher01_id = $rs->fields['voucher01_id'];
        $obj->voucher01_name = $rs->fields['voucher01_name'];
        $obj->customer_id = $rs->fields['customer_id'];
        $obj->customer_name = $rs->fields['customer_name'];
        
   
		$obj->gross = (float) $rs->fields['gross'];
		$obj->disc_item = (float) $rs->fields['disc_item'];
		$obj->voucher = (float) $rs->fields['voucher'];
		$obj->disc_paym = (float) $rs->fields['disc_paym'];
		$obj->nett = (float) $rs->fields['nett'];
		$obj->payment_line = $rs->fields['payment_line'];
		$obj->payment_type = $rs->fields['payment_type'];
		$obj->payment_value = (float) $rs->fields['payment_value'];
		$obj->payment_cardholder = $rs->fields['payment_cardholder'];
		$obj->payment_cardnumber = $rs->fields['payment_cardnumber'];
		$obj->id = $rs->fields['id'];
		$obj->branch_id = $rs->fields['branch_id'];		
		$obj->branch_name = $rs->fields['branch_name'];
		$obj->seq = $rs->fields['seq'];
		
		if ($obj->id != $lastid) {
			$alt = -1 * $alt;
			$lastid = $obj->id;
		}		
		
		$obj->alt = $alt;
		$obj->count = $rs->fields['count'];
		$obj->method = $rs->fields['method'];
		
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