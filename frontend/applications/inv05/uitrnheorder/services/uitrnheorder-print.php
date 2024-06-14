<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];


$sql = "EXEC inv05he_oc_print '$id'; ";

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
	$obj->rpt_section = $rs->fields['_section'];
	$obj->rpt_sequence = $rs->fields['_sequence'];
	$obj->rpt_rgroup = $rs->fields['_rgroup'];
	$obj->rpt_rgroupname = $rs->fields['_rgroupname'];
	$obj->heorder_id = $rs->fields['heorder_id'];
	$obj->heorder_date = $rs->fields['heorder_date'];
	$obj->heorder_dateexp = $rs->fields['heorder_dateexp'];
	$obj->heorder_descr = $rs->fields['heorder_descr'];
	$obj->region_id = $rs->fields['region_id'];
	$obj->region_name = $region_name;
	$obj->rekanan_id = $rs->fields['rekanan_id'];
	$obj->rekanan_name = $rekanan_name;
	$obj->season_id = $rs->fields['season_id'];
	$obj->season_name = $season_name;
	$obj->currency_id = $rs->fields['currency_id'];
	$obj->heorderdetil_line = $rs->fields['heorderdetil_line'];
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = $rs->fields['heinv_name'];
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