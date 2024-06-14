<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];
$doc        = $_GET['doc'];


$sql = "EXEC inv05he_mv_print '$id', '$doc' ";

$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();

/* eksekusi baris paling atas */
$region_id 	= $obj->region_id = $rs->fields['region_id'];
$rekanan_id = $obj->rekanan_id = $rs->fields['rekanan_id'];
$season_id 	= $obj->season_id = $rs->fields['season_id'];


$rsH = $conn->Execute("SELECT rekanan_name FROM master_rekanan WHERE rekanan_id='$rekanan_id '");
$rekanan_name = $rsH->fields['rekanan_name'];
$rsH = $conn->Execute("SELECT region_name FROM master_region WHERE region_id='$region_id '");
$region_name = $rsH->fields['region_name'];
$rsH = $conn->Execute("SELECT season_name FROM master_season WHERE season_id='$season_id '");
$season_name = $rsH->fields['season_name'];

while (!$rs->EOF) {
	
	unset($obj);
	$obj->rpt_rep = $rs->fields['_rep'];
	$obj->rpt_repname = $rs->fields['_repname'];
	$obj->rpt_section = $rs->fields['_section'];
	$obj->rpt_sequence = $rs->fields['_sequence'];
	$obj->rpt_rgroup = $rs->fields['_rgroup'];
	$obj->rpt_rgroupname = $rs->fields['_rgroupname'];
	$obj->hemoving_id = $rs->fields['hemoving_id'];
	$obj->hemoving_date = $rs->fields['hemoving_date'];
	$obj->hemoving_date_fr = $rs->fields['hemoving_date_fr'];
	$obj->hemoving_date_to = $rs->fields['hemoving_date_to'];
	$obj->hemoving_isprop = $rs->fields['hemoving_isprop'];
	$obj->hemoving_issend = $rs->fields['hemoving_issend'];
	$obj->hemoving_isrecv = $rs->fields['hemoving_isrecv'];
	$obj->hemoving_ispost = $rs->fields['hemoving_ispost'];
	$obj->hemoving_descr = $rs->fields['hemoving_descr'];
	$obj->hemoving_source = $rs->fields['hemoving_source'];
	$obj->hemovingtype_id = $rs->fields['hemovingtype_id'];
	$obj->branch_id_fr = $rs->fields['branch_id_fr'];
	$obj->branch_id_fr_name = $rs->fields['branch_id_fr_name'];
	$obj->branch_id_to = $rs->fields['branch_id_to'];
	$obj->branch_id_to_name = $rs->fields['branch_id_to_name'];
	$obj->region_id = $rs->fields['region_id'];
	$obj->region_id_out = $rs->fields['region_id_out'];
	$obj->region_id_name = $rs->fields['region_id_name'];
	$obj->region_id_out_name = $rs->fields['region_id_out_name'];
	$obj->rekanan_id = $rs->fields['rekanan_id'];
	$obj->rekanan_name = $rs->fields['rekanan_name'];
	$obj->currency_id = $rs->fields['currency_id'];
	$obj->line = $rs->fields['line'];
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = $rs->fields['heinv_name'];
	$obj->ref_id = $rs->fields['ref_id'];
	$obj->heinv_priceidr           = is_numeric($rs->fields['heinv_priceidr']) ? number_format($rs->fields['heinv_priceidr']) : $rs->fields['heinv_priceidr'];
	$obj->heinv_priceidr_total     = is_numeric($rs->fields['heinv_priceidr_total']) ? number_format($rs->fields['heinv_priceidr_total']) : $rs->fields['heinv_priceidr_total'];
	$obj->heinv_priceforeign       = is_numeric($rs->fields['heinv_priceforeign']) ? number_format($rs->fields['heinv_priceforeign'], 2) : $rs->fields['heinv_priceforeign'];
	$obj->heinv_priceforeign_total = is_numeric($rs->fields['heinv_priceforeign_total']) ? number_format($rs->fields['heinv_priceforeign_total'], 2) : $rs->fields['heinv_priceforeign_total'];
	$obj->heinv_priceforeignrate   = is_numeric($rs->fields['heinv_priceforeignrate']) ? number_format($rs->fields['heinv_priceforeignrate'], 2) : $rs->fields['heinv_priceforeignrate'];
	$obj->heinvctg_id = $rs->fields['heinvctg_id'];
	$obj->heinvgro_id = $rs->fields['heinvgro_id'];
	$obj->sizetag = $rs->fields['sizetag'];
	$obj->QTY = $rs->fields['QTY'];
	$obj->C01 = $rs->fields['C01'] ? $rs->fields['C01'] : "";
	$obj->C02 = $rs->fields['C02'] ? $rs->fields['C02'] : "";
	$obj->C03 = $rs->fields['C03'] ? $rs->fields['C03'] : "";
	$obj->C04 = $rs->fields['C04'] ? $rs->fields['C04'] : "";
	$obj->C05 = $rs->fields['C05'] ? $rs->fields['C05'] : "";
	$obj->C06 = $rs->fields['C06'] ? $rs->fields['C06'] : "";
	$obj->C07 = $rs->fields['C07'] ? $rs->fields['C07'] : "";
	$obj->C08 = $rs->fields['C08'] ? $rs->fields['C08'] : "";
	$obj->C09 = $rs->fields['C09'] ? $rs->fields['C09'] : "";
	$obj->C10 = $rs->fields['C10'] ? $rs->fields['C10'] : "";
	$obj->C11 = $rs->fields['C11'] ? $rs->fields['C11'] : "";
	$obj->C12 = $rs->fields['C12'] ? $rs->fields['C12'] : "";
	$obj->C13 = $rs->fields['C13'] ? $rs->fields['C13'] : "";
	$obj->C14 = $rs->fields['C14'] ? $rs->fields['C14'] : "";
	$obj->C15 = $rs->fields['C15'] ? $rs->fields['C15'] : "";
	$obj->C16 = $rs->fields['C16'] ? $rs->fields['C16'] : "";
	$obj->C17 = $rs->fields['C17'] ? $rs->fields['C17'] : "";
	$obj->C18 = $rs->fields['C18'] ? $rs->fields['C18'] : "";
	$obj->C19 = $rs->fields['C19'] ? $rs->fields['C19'] : "";
	$obj->C20 = $rs->fields['C20'] ? $rs->fields['C20'] : "";
	$obj->C21 = $rs->fields['C21'] ? $rs->fields['C21'] : "";
	$obj->C22 = $rs->fields['C22'] ? $rs->fields['C22'] : "";
	$obj->C23 = $rs->fields['C23'] ? $rs->fields['C23'] : "";
	$obj->C24 = $rs->fields['C24'] ? $rs->fields['C24'] : "";
	$obj->C25 = $rs->fields['C25'] ? $rs->fields['C25'] : "";
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