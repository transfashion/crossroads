<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	set_time_limit(100);


	$sql = "select * from transaksi_hemoving where hemoving_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);

	$objh->hemoving_id = $rs->fields['hemoving_id'];
	$objh->hemoving_source = $rs->fields['hemoving_source'];
	$objh->hemoving_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['hemoving_date']));
	$objh->hemoving_date_fr = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['hemoving_date_fr']));
	$objh->hemoving_date_to = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['hemoving_date_to']));
	$objh->hemoving_isprop = $rs->fields['hemoving_isprop'];
	$objh->hemoving_issend = $rs->fields['hemoving_issend'];
	$objh->hemoving_isrecv = $rs->fields['hemoving_isrecv'];
	$objh->hemoving_ispost = $rs->fields['hemoving_ispost'];
	$objh->hemoving_descr = $rs->fields['hemoving_descr'];
	$objh->hemoving_createby = $rs->fields['hemoving_createby'];
	$objh->hemoving_createdate = trim($rs->fields['hemoving_createdate']);
	$objh->hemoving_modifyby = $rs->fields['hemoving_modifyby'];
	$objh->hemoving_modifydate = trim($rs->fields['hemoving_modifydate']);
	$objh->hemoving_propby = $rs->fields['hemoving_propby'];
	$objh->hemoving_propdate = trim($rs->fields['hemoving_propdate']);
	$objh->hemoving_sendby = $rs->fields['hemoving_sendby'];
	$objh->hemoving_senddate = trim($rs->fields['hemoving_senddate']);
	$objh->hemoving_recvby = $rs->fields['hemoving_recvby'];
	$objh->hemoving_recvdate = trim($rs->fields['hemoving_recvdate']);
	$objh->hemoving_postby = $rs->fields['hemoving_postby'];
	$objh->hemoving_postdate = trim($rs->fields['hemoving_postdate']);
	$objh->hemovingtype_id = $rs->fields['hemovingtype_id'];
	$objh->region_id = $rs->fields['region_id'];
	$objh->region_id_out = $rs->fields['region_id_out'];
	$objh->branch_id_fr = $rs->fields['branch_id_fr'];
	$objh->branch_id_to = $rs->fields['branch_id_to'];
	$objh->convert_fr = $rs->fields['convert_fr'];
	$objh->convert_to = $rs->fields['convert_to'];
	$objh->rekanan_id = $rs->fields['rekanan_id'];
	$objh->rekanan_name = "";  //di look up di bwah
	$objh->currency_id = $rs->fields['currency_id'];
	$objh->currency_rate = 1*$rs->fields['currency_rate'];
	$objh->disc_rate = 1*$rs->fields['disc_rate'];
	$objh->invoice_id = $rs->fields['invoice_id'];
	$objh->ref_id = $rs->fields['ref_id'];
	$objh->season_id = $rs->fields['season_id'];
	$objh->season_name = "";	  //di look up di bwah
	$objh->channel_id = $rs->fields['channel_id'];	
	$objh->rowid = $rs->fields['rowid'];




	$sql  = "select A.*, heinvgro_id=B.heinvgro_id, heinvctg_id=B.heinvctg_id, heinv_sizetag=(SELECT heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id=B.heinvctg_id) ";
	$sql .= "from ";
	$sql .= "transaksi_hemovingdetil A left join master_heinv B on A.heinv_id=B.heinv_id ";
	$sql .= "where hemoving_id='$id'";
	$rs  = $conn->Execute($sql);
	$arrdataItem = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->hemoving_id = $rs->fields['hemoving_id'];
		$obj->hemovingdetil_line = $rs->fields['hemovingdetil_line'];
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinv_art = $rs->fields['heinv_art'];
		$obj->heinv_mat = $rs->fields['heinv_mat'];
		$obj->heinv_col = $rs->fields['heinv_col'];
		$obj->heinv_name = $rs->fields['heinv_name'];

		$obj->heinv_box = $rs->fields['heinv_box'];
		$obj->heinv_qtyinvoice = 1*$rs->fields['heinv_qtyinvoice'];
		
		$obj->heinvgro_id = $rs->fields['heinvgro_id'];
		$obj->heinvctg_id = $rs->fields['heinvctg_id'];
		$obj->heinv_sizetag = $rs->fields['heinv_sizetag'];
		

		
		$obj->A01 = 1*$rs->fields['A01'];
		$obj->A02 = 1*$rs->fields['A02'];
		$obj->A03 = 1*$rs->fields['A03'];
		$obj->A04 = 1*$rs->fields['A04'];
		$obj->A05 = 1*$rs->fields['A05'];
		$obj->A06 = 1*$rs->fields['A06'];
		$obj->A07 = 1*$rs->fields['A07'];
		$obj->A08 = 1*$rs->fields['A08'];
		$obj->A09 = 1*$rs->fields['A09'];
		$obj->A10 = 1*$rs->fields['A10'];
		$obj->A11 = 1*$rs->fields['A11'];
		$obj->A12 = 1*$rs->fields['A12'];
		$obj->A13 = 1*$rs->fields['A13'];
		$obj->A14 = 1*$rs->fields['A14'];
		$obj->A15 = 1*$rs->fields['A15'];
		$obj->A16 = 1*$rs->fields['A16'];
		$obj->A17 = 1*$rs->fields['A17'];
		$obj->A18 = 1*$rs->fields['A18'];
		$obj->A19 = 1*$rs->fields['A19'];
		$obj->A20 = 1*$rs->fields['A20'];
		$obj->A21 = 1*$rs->fields['A21'];
		$obj->A22 = 1*$rs->fields['A22'];
		$obj->A23 = 1*$rs->fields['A23'];
		$obj->A24 = 1*$rs->fields['A24'];
		$obj->A25 = 1*$rs->fields['A25'];

		$obj->B01 = 1*$rs->fields['B01'];
		$obj->B02 = 1*$rs->fields['B02'];
		$obj->B03 = 1*$rs->fields['B03'];
		$obj->B04 = 1*$rs->fields['B04'];
		$obj->B05 = 1*$rs->fields['B05'];
		$obj->B06 = 1*$rs->fields['B06'];
		$obj->B07 = 1*$rs->fields['B07'];
		$obj->B08 = 1*$rs->fields['B08'];
		$obj->B09 = 1*$rs->fields['B09'];
		$obj->B10 = 1*$rs->fields['B10'];
		$obj->B11 = 1*$rs->fields['B11'];
		$obj->B12 = 1*$rs->fields['B12'];
		$obj->B13 = 1*$rs->fields['B13'];
		$obj->B14 = 1*$rs->fields['B14'];
		$obj->B15 = 1*$rs->fields['B15'];
		$obj->B16 = 1*$rs->fields['B16'];
		$obj->B17 = 1*$rs->fields['B17'];
		$obj->B18 = 1*$rs->fields['B18'];
		$obj->B19 = 1*$rs->fields['B19'];
		$obj->B20 = 1*$rs->fields['B20'];
		$obj->B21 = 1*$rs->fields['B21'];
		$obj->B22 = 1*$rs->fields['B22'];
		$obj->B23 = 1*$rs->fields['B23'];
		$obj->B24 = 1*$rs->fields['B24'];
		$obj->B25 = 1*$rs->fields['B25'];

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

		$obj->heinv_box = $rs->fields['heinv_box'];
		$obj->heinv_invoiceqty = 1*$rs->fields['heinv_invoiceqty'];
		$obj->heinv_invoiceid = $rs->fields['heinv_invoiceid'];

		
		$qty_prop = 0;
		$qty_send = 0;
		$qty_recv = 0;
		for ($i=1; $i<=25; $i++) {
			$fname = str_pad($i, 2, "0", STR_PAD_LEFT);  
			$qty_prop += 1*$rs->fields['A'.$fname];
			$qty_send += 1*$rs->fields['B'.$fname];
			$qty_recv += 1*$rs->fields['C'.$fname];
		} 
		
		
		
		$obj->heinv_qtyprop = 1*$qty_prop;
		$obj->heinv_qtysend = 1*$qty_send;
		$obj->heinv_qtyrecv = 1*$qty_recv;
		


		$obj->heinv_price 		= (float) $rs->fields['heinv_price'];
		$obj->heinv_disc 		= (float) $rs->fields['heinv_disc'];		
		$obj->heinv_subtotal 	= (float) $obj->heinv_qtyrecv * (((100-$obj->heinv_disc)/100) * $obj->heinv_price);
		$obj->heinv_priceidr 	= (float) $objh->currency_rate * $rs->fields['heinv_price'];
		$obj->heinv_subtotalidr = (float) $objh->currency_rate * $obj->heinv_subtotal;



		$obj->ref_id = $rs->fields['ref_id'];		
		$obj->ref_line = 1*$rs->fields['ref_line'];

		//print " - ---------**".  $obj->heinv_qtyprop . " " . $obj->heinv_qtysend ." ". $obj->heinv_qtyrecv . " --------------\n";
		
		$arrdataItem[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilItem'] = $arrdataItem;
	




	$sql  = "select A.*, heinvgro_id=B.heinvgro_id, heinvctg_id=B.heinvctg_id, heinv_sizetag=(SELECT heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id=B.heinvctg_id) ";
	$sql .= "from ";
	$sql .= "transaksi_hemovingexcp A left join master_heinv B on A.heinv_id=B.heinv_id ";
	$sql .= "where hemoving_id='$id'";	
	$rs  = $conn->Execute($sql);
	$arrdataExcp = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->hemoving_id = $rs->fields['hemoving_id'];
		$obj->hemovingdetilex_line = 1*$rs->fields['hemovingdetilex_line'];
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinv_art = $rs->fields['heinv_art'];
		$obj->heinv_mat = $rs->fields['heinv_mat'];
		$obj->heinv_col = $rs->fields['heinv_col'];
		$obj->heinv_name = $rs->fields['heinv_name'];
		$obj->heinv_box = $rs->fields['heinv_box'];
		$obj->heinv_qtyinvoice = 1*$rs->fields['heinv_qtyinvoice'];
				
		$obj->heinvgro_id = $rs->fields['heinvgro_id'];
		$obj->heinvctg_id = $rs->fields['heinvctg_id'];
		$obj->heinv_sizetag = $rs->fields['heinv_sizetag'];		
		
	
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
		

		$obj->heinv_box = $rs->fields['heinv_box'];
		$obj->heinv_invoiceqty = 1*$rs->fields['heinv_invoiceqty'];
		$obj->heinv_invoiceid = $rs->fields['heinv_invoiceid'];

		$qty_recv = 0;
		for ($i=1; $i<=25; $i++) {
			$fname = str_pad($i, 2, "0", STR_PAD_LEFT);  
			$qty_recv += 1*$rs->fields['C'.$fname];
		} 
		
		$obj->heinv_qtyrecv = 1*$qty_recv;
		
		
		$obj->heinv_price 		= (float) $rs->fields['heinv_price'];
		$obj->heinv_disc 		= (float) $rs->fields['heinv_disc'];		
		$obj->heinv_subtotal 	= (float) $obj->heinv_qtyrecv * (((100-$obj->heinv_disc)/100) * $obj->heinv_price);
		$obj->heinv_priceidr 	= (float) $objh->currency_rate * $rs->fields['heinv_price'];
		$obj->heinv_subtotalidr = (float) $objh->currency_rate * $obj->heinv_subtotal;

		
		$obj->ref_id = $rs->fields['ref_id'];		
		$obj->ref_line = 1*$rs->fields['ref_line'];
		$obj->ref_iscommit = 1*$rs->fields['ref_iscommit'];

		
		$arrdataExcp[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilException'] = $arrdataExcp;
	
	
	
	/* Look Up Data Header */
	$sql = "select rekanan_name from master_rekanan where rekanan_id='".$objh->rekanan_id."'";
	$rs  = $conn->Execute($sql);
	$objh->rekanan_name = $rs->fields['rekanan_name'];
	
	$sql = "select season_name from master_season where season_id='".$objh->season_id."'";
	$rs  = $conn->Execute($sql);
	$objh->season_name = $rs->fields['season_name'];
	
	
	
	
	$data[0]['H'] = $objh;


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