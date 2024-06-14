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
		
		SQLUTIL::BuildCriteria($SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_inventory_doc_id', 'hemoving_id', "refParser");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', 'region_id', "{criteria_value}");	
	}

	$data = array();

	$sql = "SELECT * FROM transaksi_hemovingdetil WHERE $SQL_CRITERIA";
	
	
	$rs  = $conn->Execute($sql);
	 

	while (!$rs->EOF) {
	 
	
		unset($obj);
		$obj->branch_id = '';
		$obj->branch_name = ''; 
		$obj->BEG 	= 0; 
		$obj->RV 	= 0; 
		$obj->TOUT 	= 0; 
		$obj->TIN 	= 0; 
		$obj->TTS 	= 0; 
		$obj->SL 	= 0; 
		$obj->DO 	= 0; 
		$obj->AJ 	= 0; 
		$obj->AS 	= 0; 
		$obj->OTHER = 0; 
		$obj->END 	= 0; 
		$obj->heinv_id	= trim($rs->fields['heinv_id']); 
		
		
	
		
		$heinv_id = trim($rs->fields['heinv_id']);
		$sqlI = "SELECT heinv_art,heinv_mat,heinv_col,heinv_name,heinvctg_id, season_id,heinv_price01, heinv_pricedisc01 FROM master_heinv WHERE heinv_id = '$heinv_id'";
		$rsI = $conn->execute($sqlI);

		
		
		
		$obj->heinv_art	= trim($rsI->fields['heinv_art']); 
		$obj->heinv_mat	= trim($rsI->fields['heinv_mat']); 
		$obj->heinv_col	= trim($rsI->fields['heinv_col']); 
		$obj->heinv_name	= trim($rsI->fields['heinv_name']); 


		
		$obj->season_id	= trim($rsI->fields['season_id']); 
		$obj->heinvctg_id	= trim($rsI->fields['heinvctg_id']); 
		
		$heinvctg_id = trim($rsI->fields['heinvctg_id']);
		$sqlC = "SELECT heinvctg_name,heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id = '$heinvctg_id'";
		$rsC = $conn->execute($sqlC);
		
		$obj->heinvctg_name =  $rsC->fields['heinvctg_name'];
		$obj->heinv_sizetag = (int) $rsC->fields['heinvctg_sizetag'];
		
		
		$obj->heinv_price01 = (int) $rsI->fields['heinv_price01'];
		$obj->heinv_pricedisc01 = (int) $rsI->fields['heinv_pricedisc01'];
		$obj->heinv_isSP =0;
		

		$obj->C01 = (int) $rs->fields['C01'];
		$obj->C02 = (int) $rs->fields['C02'];
		$obj->C03 = (int) $rs->fields['C03'];
		$obj->C04 = (int) $rs->fields['C04'];
		$obj->C05 = (int) $rs->fields['C05'];
		$obj->C06 = (int) $rs->fields['C06'];
		$obj->C07 = (int) $rs->fields['C07'];
		$obj->C08 = (int) $rs->fields['C08'];
		$obj->C09 = (int) $rs->fields['C09'];
		$obj->C10 = (int) $rs->fields['C10'];
		$obj->C11 = (int) $rs->fields['C11'];
		$obj->C12 = (int) $rs->fields['C12'];
		$obj->C13 = (int) $rs->fields['C13'];
		$obj->C14 = (int) $rs->fields['C14'];
		$obj->C15 = (int) $rs->fields['C15'];
		$obj->C16 = (int) $rs->fields['C16'];
		$obj->C17 = (int) $rs->fields['C17'];
		$obj->C18 = (int) $rs->fields['C18'];
		$obj->C19 = (int) $rs->fields['C19'];
		$obj->C20 = (int) $rs->fields['C20'];
		$obj->C21 = (int) $rs->fields['C21'];
		$obj->C22 = (int) $rs->fields['C22'];
		$obj->C23 = (int) $rs->fields['C23'];
		$obj->C24 = (int) $rs->fields['C24'];
		$obj->C25 = (int) $rs->fields['C25'];	
	
	
		$data[] = $obj;
		$rs->MoveNext();
	}
	

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>