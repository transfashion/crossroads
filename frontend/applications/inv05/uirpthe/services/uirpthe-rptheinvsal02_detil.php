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
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
		
	}	



	$sql = "
	
			set nocount on;
	
			DECLARE @region_id as varchar(5);
			DECLARE @date as smalldatetime;
			
			SET @region_id = '$region_id';
			SET @date = '$dateend';
			
			EXEC poshe_RptSalesSummary_bybranch @region_id, @date
	";


		$waktu = explode("-",$dateend);
		$thn = $waktu[0];
		$bln = 1*$waktu[1];

//	print $sql;
	
	
	
	$data = array();
	$rs   = $conn->Execute($sql);
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->branch_id				= $rs->fields['branch_id'];
		$obj->branch_name			= $rs->fields['branch_name'];
		$obj->sales_qty				= (float) $rs->fields['sales_qty'];
		$obj->sales_gross			= (float) $rs->fields['sales_gross'];
		$obj->sales_nett			= (float) $rs->fields['sales_nett'];
		$obj->extrapolated_qty		= (float) $rs->fields['extrapolated_qty'];
		$obj->extrapolated_gross	= (float) $rs->fields['extrapolated_gross'];
		$obj->extrapolated_nett		= (float) $rs->fields['extrapolated_nett'];
		
		$branch_id = $rs->fields['branch_id'];

		
		$SQLT = "
		SELECT target_value = sum(targetdetil_valuetarget) FROM transaksi_target A inner join transaksi_targetdetil B on A.target_id = B.target_id
		AND A.region_id = '$region_id' and B.branch_id = '$branch_id' and year(A.periode_id)='$thn' and month(A.periode_id)='$bln'  ";
		
		$rsT = $conn->execute($SQLT);
		
		
		$obj->target_value			= (float) $rsT->fields['target_value'];
	 
		$obj->target_percent		= (float) $rs->fields['target_percent'];
		
		$SQLB = "
		SELECT budget_value = sum(targetdetil_valuebudget) FROM transaksi_target A inner join transaksi_targetdetil B on A.target_id = B.target_id
		AND A.region_id = '$region_id' and B.branch_id = '$branch_id' and year(A.periode_id)='$thn' and month(A.periode_id)='$bln'  ";
	
		$rsB = $conn->execute($SQLB);
		$obj->budget_value			= (float) $rsB->fields['budget_value'];
		$obj->budget_percent			= (float) $rs->fields['budget_percent'];
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