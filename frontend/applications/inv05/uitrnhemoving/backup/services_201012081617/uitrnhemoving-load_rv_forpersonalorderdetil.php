<?
if (!defined('__SERVICE__')) {
	die("access denied");
}




$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];



$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria  */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_hemoving_id', 'hemoving_id', " %s = '%s' ");
	
}



$sql = "SELECT * FROM transaksi_hemovingdetil WHERE $SQL_CRITERIA ";
$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->hemoving_id = $rs->fields['hemoving_id'];
	$obj->hemovingdetil_line = $rs->fields['hemovingdetil_line'];
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = $rs->fields['heinv_name'];
	$obj->heinv_price = (float) $rs->fields['heinv_price'];
	$obj->heinv_disc  = 1*$rs->fields['heinv_disc'];
	$obj->heinv_invoiceid  = $rs->fields['heinv_invoiceid'];
	

	$sql = "SELECT * FROM master_heinv WHERE heinv_id='".$obj->heinv_id."'";
	$rsI = $conn->Execute($sql);
	$obj->heinvgro_id = $rsI->fields['heinvgro_id'];
	$obj->heinvctg_id = $rsI->fields['heinvctg_id'];
	
	
	$sql = "SELECT * FROM master_heinvctg WHERE heinvctg_id='".$obj->heinvctg_id."'";
	$rsI = $conn->Execute($sql);
	$obj->heinv_sizetag = $rsI->fields['heinvctg_sizetag'];

	
	
	$obj->C01 = 1*$rs->fields['C01'];
	$obj->C02 = 1*$rs->fields['C02'];
	$obj->C03 = 1*$rs->fields['C03'];
	$obj->C04 = 1*$rs->fields['C04'];
	$obj->C05 = 1*$rs->fields['C05'];
	$obj->C06 = 1*$rs->fields['C06'];
	$obj->C07 = 1*$rs->fields['C07'];
	$obj->C08 = 1*$rs->fields['C08'];
	$obj->C09 = 1*$rs->fields['C09'];
	$obj->C10 = 1*$rs->fields['C10'];
	$obj->C11 = 1*$rs->fields['C11'];
	$obj->C12 = 1*$rs->fields['C12'];
	$obj->C13 = 1*$rs->fields['C13'];
	$obj->C14 = 1*$rs->fields['C14'];
	$obj->C15 = 1*$rs->fields['C15'];
	$obj->C16 = 1*$rs->fields['C16'];
	$obj->C17 = 1*$rs->fields['C17'];
	$obj->C18 = 1*$rs->fields['C18'];
	$obj->C19 = 1*$rs->fields['C19'];
	$obj->C20 = 1*$rs->fields['C20'];
	$obj->C21 = 1*$rs->fields['C21'];
	$obj->C22 = 1*$rs->fields['C22'];
	$obj->C23 = 1*$rs->fields['C23'];
	$obj->C24 = 1*$rs->fields['C24'];
	$obj->C25 = 1*$rs->fields['C25'];
	
	$qty = 0;
	for ($i=1; $i<=25; $i++) {
		$fname = str_pad($i, 2, "0", STR_PAD_LEFT);  
		$qty += 1*$rs->fields['C'.$fname];
	} 
	
	$obj->heinv_qty = 1*$qty;
		


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