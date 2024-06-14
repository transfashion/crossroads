<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	set_time_limit(100);


	$sql = "select * from transaksi_heinvprintbarcode where batch_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->batch_id = $rs->fields['batch_id'];;
	$objh->batch_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['batch_date']));
	$objh->batch_descr = $rs->fields['batch_descr'];
	$objh->batch_isposted = 1*$rs->fields['batch_isposted'];
	$objh->batch_isean = 1*$rs->fields['batch_isean'];
        $region_id = $rs->fields['region_id'];	
	$objh->region_id = $rs->fields['region_id'];	
	$objh->rowid = $rs->fields['rowid'];
	
 
	$data[0]['H'] = $objh;






	$sql  = "
	SELECT *,heinv_sizetag=(SELECT heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id=transaksi_heinvprintbarcodedetil.heinvctg_id   and region_id = $region_id ) 
	FROM transaksi_heinvprintbarcodedetil ";
	$sql .= "where batch_id='$id'";
	$rs  = $conn->Execute($sql);
	$arrdataItem = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->batch_id = $rs->fields['batch_id'];
		$obj->batchdetil_line = 1*$rs->fields['batchdetil_line'];
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinv_art = $rs->fields['heinv_art'];
		$obj->heinv_mat = $rs->fields['heinv_mat'];
		$obj->heinv_col = $rs->fields['heinv_col'];
		$obj->heinv_name = $rs->fields['heinv_name'];
		$obj->season_id = $rs->fields['season_id'];
		
		$heinv_id = $obj->heinv_id;
		
		$sqlCtg = "select heinvctg_id FROM master_heinv where heinv_id = '$heinv_id'";
		$rsCtg = $conn->execute($sqlCtg);
		
		$obj->heinvctg_id = $rsCtg->fields['heinvctg_id'];
		
 
		
		
		$heinvctg_id  = $obj->heinvctg_id;
		$sqlCat = "SELECT heinvctg_name,heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id = '$heinvctg_id'";
		$rsC = $conn->execute($sqlCat);
		
		
		$heinvctg_name = $rsC->fields['heinvctg_name'];
		$obj->heinvctg_name = $heinvctg_name;
		
	 
		$obj->heinv_isSP = $rs->fields['heinv_isSP'];
	 
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
		
		$obj->heinv_sizetag = $rsC->fields['heinvctg_sizetag'];



		
		
		$qty_print = 0;
		
		for ($i=1; $i<=25; $i++) {
			$fname = str_pad($i, 2, "0", STR_PAD_LEFT);  
			$qty_print += 1*$rs->fields['C'.$fname];
		 
		} 
		
			$obj->heinv_qtyprint = 1*$qty_print;
		
		$heinv_id = $rs->fields['heinv_id'];
 
		//price ini select dari master_heinv
	$sqlPrice = "SELECT heinv_priceori,heinv_price01,heinv_pricedisc01 FROM master_heinv WHERE heinv_id ='$heinv_id'";
	$rsR = $conn->Execute($sqlPrice);
	$obj->heinv_price01 = 1*$rsR->fields['heinv_price01'];
	$obj->heinv_pricedisc01 = 1*$rsR->fields['heinv_pricedisc01'];
	$obj->heinv_priceori= 1*$rsR->fields['heinv_priceori']; 
			
		$arrdataItem[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilItem'] = $arrdataItem;
	
 


/* ====================================================================================================== */


$sql = "select * from transaksi_tprop where id='$id'";
$rs  = $conn->Execute($sql);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->prop_id = trim($rs->fields['id']);
	$obj->prop_line = 1*$rs->fields['prop_line'];
	$obj->prop_name = $rs->fields['prop_name'];
	$obj->prop_descr = $rs->fields['prop_descr'];
	$obj->prop_value = $rs->fields['prop_value'];
	$obj->rowid = $rs->fields['rowid'];	

	$arrdata[] = $obj;
	$rs->MoveNext();
}
$data[0]['D']['Prop'] = $arrdata;


$i = 1;
$sql = "select * from transaksi_tlog where id='$id' order by log_date DESC";
$rs  = $conn->Execute($sql);

$num = $rs->recordCount();
$rs  = $conn->SelectLimit($sql, 25);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->id = trim($rs->fields['id']);
	$obj->log_line = $i; //$rs->fields['log_line'];
	$obj->log_date = trim($rs->fields['log_date']);
	$obj->log_action = $rs->fields['log_action'];
	$obj->log_descr = $rs->fields['log_descr'];
	$obj->log_table = $rs->fields['log_table'];
	$obj->log_lastvalue = $rs->fields['log_lastvalue'];
	$obj->log_username = $rs->fields['log_username'];
	$obj->rowid = $rs->fields['rowid'];
	$arrdata[] = $obj;
	$rs->MoveNext();
	$i++;
}
if ($num>25) {
	unset($obj);
	$obj->id = trim($rs->fields['id']);
	$obj->log_line = $i;
	$obj->log_date = trim($rs->fields['log_date']);
	$obj->log_action = "GABUNGAN";
	$obj->log_descr = "Log lainnya tidak ditampilkan";
	$obj->log_table = "";
	$obj->log_lastvalue = "";
	$obj->log_username = "SYSTEM";
	$obj->rowid = "0";
	$arrdata[] = $obj;
}
$data[0]['D']['Log'] = $arrdata;




$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>
