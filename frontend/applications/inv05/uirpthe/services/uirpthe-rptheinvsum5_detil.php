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
	
	
	
	$sql_b="SELECT branch_id, branch_name FROM master_branch";
	$rs_b=$conn->execute($sql_b);
	
	$branchs = array();
	WHILE (!$rs_b->EOF)
	{
	 	$branchs[$rs_b->fields['branch_id']]=$rs_b->fields['branch_name'];
	 
	 	$rs_b->MoveNext();
	 }
	 
	
	//A52332F2-51A9-485D-8D81-C2BDD3A7ACB4|10|32|0
	$args = explode("|", $ids);
	$cacheid = $args[0];
	$page    = $args[1];
	$limit	 = $args[2];
	$start   = $args[3];


	$sql = "
		select * from dbo.cache_heinvsummary 
		WHERE cacheid='$cacheid' and REPSECTION = 'ITEM' ";
	//$rs = $conn->Execute($sql);
	//$totalCount = $rs->recordCount();
	$rs = $conn->SelectLimit($sql, $limit, $start);
	$data = array();
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->REPSEQ = $rs->fields['REPSEQ']; 
		$obj->REPSECTION = $rs->fields['REPSECTION']; 
		$obj->REPSECTIONSEQ = $rs->fields['REPSECTIONSEQ']; 
		$obj->region_id = $rs->fields['region_id']; 
		$obj->branch_id = $rs->fields['branch_id']; 
		$obj->branch_name =  $branchs[$rs->fields['branch_id']];

		
		$obj->season_id = $rs->fields['season_id']; 
		$obj->season_group = $rs->fields['season_group']; 
		$obj->heinvgro_id = $rs->fields['heinvgro_id']; 
		$obj->heinvgro_name = $rs->fields['heinvgro_name']; 
		$obj->heinvctg_id = $rs->fields['heinvctg_id']; 
		$obj->heinvctg_name = $rs->fields['heinvctg_name']; 
		$obj->heinvctg_namegroup = $rs->fields['heinvctg_namegroup']; 
		$obj->heinv_id = $rs->fields['heinv_id']; 
		$obj->heinv_name = $rs->fields['heinv_name']; 
		$obj->heinv_art = $rs->fields['heinv_art']; 
		$obj->heinv_mat = $rs->fields['heinv_mat']; 
		$obj->heinv_col = $rs->fields['heinv_col']; 
		$obj->firstprice		= (float) $rs->fields['heinv_priceori']; 
		$obj->currentprice		= (float) $rs->fields['heinv_price01']; 
		$obj->currentdisc		= (float) $rs->fields['heinv_pricedisc01']; 
		$obj->transtype = 'SL'; 		
		$obj->qty = (int) $rs->fields['SL']; 

		$data[] = $obj;
		
		
		
		
		unset($obj);
		$obj->REPSEQ = $rs->fields['REPSEQ']; 
		$obj->REPSECTION = $rs->fields['REPSECTION']; 
		$obj->REPSECTIONSEQ = $rs->fields['REPSECTIONSEQ']; 
		$obj->region_id = $rs->fields['region_id']; 
		$obj->branch_id = $rs->fields['branch_id']; 
		$obj->branch_name =  $branchs[$rs->fields['branch_id']];

		
		$obj->season_id = $rs->fields['season_id']; 
		$obj->season_group = $rs->fields['season_group']; 
		$obj->heinvgro_id = $rs->fields['heinvgro_id']; 
		$obj->heinvgro_name = $rs->fields['heinvgro_name']; 
		$obj->heinvctg_id = $rs->fields['heinvctg_id']; 
		$obj->heinvctg_name = $rs->fields['heinvctg_name']; 
		$obj->heinvctg_namegroup = $rs->fields['heinvctg_namegroup']; 
		$obj->heinv_id = $rs->fields['heinv_id']; 
		$obj->heinv_name = $rs->fields['heinv_name']; 
		$obj->heinv_art = $rs->fields['heinv_art']; 
		$obj->heinv_mat = $rs->fields['heinv_mat']; 
		$obj->heinv_col = $rs->fields['heinv_col']; 
		$obj->firstprice		= (float) $rs->fields['heinv_priceori']; 
		$obj->currentprice		= (float) $rs->fields['heinv_price01']; 
		$obj->currentdisc		= (float) $rs->fields['heinv_pricedisc01']; 
		$obj->transtype = 'END'; 		
		$obj->qty = (int) $rs->fields['END']; 

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