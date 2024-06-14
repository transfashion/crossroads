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
		select * from dbo.cache_heinvsalethru 
		WHERE cacheid='$cacheid' ORDER BY   heinv_art";
	//$rs = $conn->Execute($sql);
	//$totalCount = $rs->recordCount();
	$rs = $conn->SelectLimit($sql, $limit, $start);
	$data = array();
	
	while (!$rs->EOF) {
	 
  
		unset($obj);

		$heinv_id = $rs->fields['heinv_id']; 

		$SQLG = "SELECT * FROM master_heinv WHERE heinv_id = '$heinv_id'";
		$rsG=$conn->execute($SQLG);
		$region_id = $rsG->fields['region_id'];
			
		//$obj->region_id = $region_id; 
		$obj->heinv_id 							= $heinv_id; 
		$obj->heinv_art 						= $rs->fields['heinv_art']; 
		$obj->heinv_mat 						= $rs->fields['heinv_mat']; 
		$obj->heinv_col 						= $rs->fields['heinv_col']; 
		$obj->heinv_name 						= $rs->fields['heinv_name']; 		
		$obj->heinv_priceori 					= (float) $rs->fields['heinv_priceori']; 
		$obj->heinv_price01 					= (float) $rs->fields['heinv_price01']; 
		$obj->heinv_pricedisc01 				= (float) $rs->fields['heinv_pricedisc01']; 		

		$obj->heinvctg_name 					= $rs->fields['heinvctg_name'];
		$obj->heinvctg_namegroup 				= $rs->fields['heinvctg_namegroup'];
		
		$obj->heinvgro_name 					= $rs->fields['heinvgro_name'];
		$obj->heinvctg_sizetag 					= $rs->fields['heinvctg_sizetag'];
		
		
		$obj->season_id 						= $rs->fields['season_id']; 
		$obj->season_group 						= $rs->fields['season_group']; 

		$obj->heinv_priceori 					= 1*$rs->fields['heinv_priceori']; 
		$obj->heinv_pricenett 					= $rs->fields['heinv_pricenett']; 
		
		$obj->heinv_pricedisc01group	        = $rs->fields['heinv_pricedisc01group'];
		$obj->heinv_pricevalue					= $rs->fields['heinv_pricevalue'];
		
		$obj->qty_saldo_lastyear 				= $rs->fields['qty_saldo_lastyear']; 
		$obj->qty_received_lastyear 			= $rs->fields['qty_received_lastyear']; 
		$obj->qty_sl 							= $rs->fields['qty_sl']; 
		$obj->BEG_STOCK 						= $rs->fields['BEG_STOCK']; 
		$obj->BEG_SL 							= $rs->fields['BEG_SL']; 
		$obj->BEG_SEA 							= $rs->fields['BEG_SEA']; 
		
		$obj->S1_RV 							= $rs->fields['S1_RV']; 
		$obj->S1_SL 							= $rs->fields['S1_SL']; 
		$obj->S1_END 							= $rs->fields['S1_END']; 
 
		$obj->S2_RV 							= $rs->fields['S2_RV']; 
		$obj->S2_SL 							= $rs->fields['S2_SL']; 
		$obj->S2_END 							= $rs->fields['S2_END']; 
		
		$obj->S3_RV 							= $rs->fields['S3_RV']; 
		$obj->S3_SL 							= $rs->fields['S3_SL']; 
		$obj->S3_END 							= $rs->fields['S3_END']; 
		
		$obj->S4_RV 							= $rs->fields['S4_RV']; 
		$obj->S4_SL 							= $rs->fields['S4_SL']; 
		$obj->S4_END 							= $rs->fields['S4_END']; 
		
		$obj->S5_RV 							= $rs->fields['S5_RV']; 
		$obj->S5_SL 							= $rs->fields['S5_SL']; 
		$obj->S5_END 							= $rs->fields['S5_END']; 
		
		$obj->qty_slo 							= $rs->fields['qty_slo']; 
		$obj->qty_to 							= $rs->fields['qty_to']; 
		$obj->qty_ti 							= $rs->fields['qty_ti']; 
		$obj->qty_tt 							= $rs->fields['qty_tt']; 
		$obj->qty_do 							= $rs->fields['qty_do']; 
		$obj->qty_aj 							= $rs->fields['qty_aj']; 
		$obj->qty_as 							= $rs->fields['qty_as']; 
		$obj->qty_ot 							= $rs->fields['qty_ot']; 		
		$obj->qty_end 							= $rs->fields['qty_end']; 		
		 
		$obj->rowid = $rs->fields['rowid'];



		
		
		
		
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
