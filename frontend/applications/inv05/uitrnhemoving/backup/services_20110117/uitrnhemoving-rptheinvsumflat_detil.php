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
		$ctg_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_ctgid',   '', "{criteria_value}");
	}	
	
	
	
	//A52332F2-51A9-485D-8D81-C2BDD3A7ACB4|10|32|0
	$args = explode("|", $ids);
	$cacheid = $args[0];
	$page    = $args[1];
	$limit	 = $args[2];
	$start   = $args[3];


	$sql = "select *,
			heinv_price01 = isnull((select heinv_price01 FROM master_heinv WHERE heinv_id = cache_heinvsummary.heinv_id),0),
				heinv_pricedisc01 = isnull((select heinv_pricedisc01 FROM master_heinv WHERE heinv_id = cache_heinvsummary.heinv_id),0)
		 from dbo.cache_heinvsummary WHERE cacheid='$cacheid'  and heinvctg_id='$ctg_id' ORDER BY REPSEQ, heinvctg_name, heinvctg_id, REPSECTIONSEQ";
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
		$obj->season_id = $rs->fields['season_id']; 
		$obj->heinvgro_id = $rs->fields['heinvgro_id']; 
		$obj->heinvctg_id = $rs->fields['heinvctg_id']; 
		$obj->heinvctg_name = $rs->fields['heinvctg_name']; 
		$obj->heinv_id = $rs->fields['heinv_id']; 
		$obj->heinv_name = $rs->fields['heinv_name']; 
		$obj->heinv_art = $rs->fields['heinv_art']; 
		$obj->heinv_mat = $rs->fields['heinv_mat']; 
		$obj->heinv_col = $rs->fields['heinv_col']; 
		$obj->heinv_sizetag = $rs->fields['heinv_sizetag']; 
		IF ($obj->REPSECTION="ITEM")
		{
			 $obj->heinv_price01 = 1*$rs->fields['heinv_price01']; 
			 $obj->heinv_pricedisc01 = 1*$rs->fields['heinv_pricedisc01']; 
		 }
		 else
		 {
			 $obj->heinv_price01 = "";
			 $obj->heinv_pricedisc01 = "";
		  }
		
		$obj->BEG = $rs->fields['BEG']; 
		$obj->RV = $rs->fields['RV']; 
		$obj->TOUT = $rs->fields['TOUT']; 
		$obj->TIN = $rs->fields['TIN']; 
		$obj->TTS = $rs->fields['TTS']; 
		$obj->SL = $rs->fields['SL']; 
		$obj->DO = $rs->fields['DO']; 
		$obj->AJ = $rs->fields['AJ']; 
		$obj->AS = $rs->fields['AS']; 
		$obj->OTHER = $rs->fields['OTHER']; 
		$obj->END = $rs->fields['END']; 
		$obj->C01 = $rs->fields['C01'] ? $rs->fields['C01'] : '';
		$obj->C02 = $rs->fields['C02'] ? $rs->fields['C02'] : '';
		$obj->C03 = $rs->fields['C03'] ? $rs->fields['C03'] : '';
		$obj->C04 = $rs->fields['C04'] ? $rs->fields['C04'] : '';
		$obj->C05 = $rs->fields['C05'] ? $rs->fields['C05'] : '';
		$obj->C06 = $rs->fields['C06'] ? $rs->fields['C06'] : '';
		$obj->C07 = $rs->fields['C07'] ? $rs->fields['C07'] : '';
		$obj->C08 = $rs->fields['C08'] ? $rs->fields['C08'] : '';
		$obj->C09 = $rs->fields['C09'] ? $rs->fields['C09'] : '';
		$obj->C10 = $rs->fields['C10'] ? $rs->fields['C10'] : '';
		$obj->C11 = $rs->fields['C11'] ? $rs->fields['C11'] : '';
		$obj->C12 = $rs->fields['C12'] ? $rs->fields['C12'] : '';
		$obj->C13 = $rs->fields['C13'] ? $rs->fields['C13'] : '';
		$obj->C14 = $rs->fields['C14'] ? $rs->fields['C14'] : '';
		$obj->C15 = $rs->fields['C15'] ? $rs->fields['C15'] : '';
		$obj->C16 = $rs->fields['C16'] ? $rs->fields['C16'] : '';
		$obj->C17 = $rs->fields['C17'] ? $rs->fields['C17'] : '';
		$obj->C18 = $rs->fields['C18'] ? $rs->fields['C18'] : '';
		$obj->C19 = $rs->fields['C19'] ? $rs->fields['C19'] : '';
		$obj->C20 = $rs->fields['C20'] ? $rs->fields['C20'] : '';
		$obj->C21 = $rs->fields['C21'] ? $rs->fields['C21'] : '';
		$obj->C22 = $rs->fields['C22'] ? $rs->fields['C22'] : '';
		$obj->C23 = $rs->fields['C23'] ? $rs->fields['C23'] : '';
		$obj->C24 = $rs->fields['C24'] ? $rs->fields['C24'] : '';
		$obj->C25 = $rs->fields['C25'] ? $rs->fields['C25'] : '';
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