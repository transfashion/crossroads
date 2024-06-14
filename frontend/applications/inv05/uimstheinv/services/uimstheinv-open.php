<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];


	unset($data);

	set_time_limit(100);


	$sql = "select * from master_heinv where heinv_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);

	$objh->heinv_id 		= $rs->fields['heinv_id'];
	$objh->heinv_art 		= $rs->fields['heinv_art'];
	$objh->heinv_mat 		= $rs->fields['heinv_mat'];
	$objh->heinv_col 		= $rs->fields['heinv_col'];
	$objh->heinv_name 		= $rs->fields['heinv_name'];
	$objh->heinv_descr		= $rs->fields['heinv_descr'];
	$objh->heinv_gtype		= $rs->fields['heinv_gtype'];
	$objh->heinv_isdisabled = $rs->fields['heinv_isdisabled'];
	$objh->heinv_isassembly = $rs->fields['heinv_isassembly'];
	$objh->heinv_iskonsinyasi = $rs->fields['heinv_iskonsinyasi'];
	$objh->ref_id 			= $rs->fields['ref_id'];
	$objh->heinv_priceori 	= (float) $rs->fields['heinv_priceori'];
	$objh->heinv_price01 	= (float) $rs->fields['heinv_price01'];
	$objh->heinv_pricedisc01 = (float) $rs->fields['heinv_pricedisc01'];


	// Tutup harga
	//$objh->heinv_price01     = 0;
	//$objh->heinv_pricedisc01 = 0;



	$objh->heinv_price02 	= (float) $rs->fields['heinv_price02'];
	$objh->heinv_pricedisc02 = (float) $rs->fields['heinv_pricedisc02'];
	$objh->heinv_price03 	= (float) $rs->fields['heinv_price03'];
	$objh->heinv_pricedisc03 = (float) $rs->fields['heinv_pricedisc03'];
	$objh->heinv_price04 	= (float) $rs->fields['heinv_price04'];
	$objh->heinv_pricedisc04 = (float) $rs->fields['heinv_pricedisc04'];
	$objh->heinv_price05 	= (float) $rs->fields['heinv_price05'];
	$objh->heinv_pricedisc05 = (float) $rs->fields['heinv_pricedisc05'];
	$objh->heinv_createby 	= $rs->fields['heinv_createby'];
	$objh->heinv_createdate = SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['heinv_createdate']));
	$objh->heinv_modifyby 	= $rs->fields['heinv_modifyby'];
	$objh->heinv_modifydate = SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['heinv_modifydate']));
	$objh->heinvgro_id 		= $rs->fields['heinvgro_id'];
	$objh->heinvctg_id 		= $rs->fields['heinvctg_id'];
	$objh->season_id 		= $rs->fields['season_id'];
	$objh->region_id 		= $rs->fields['region_id'];
	$objh->rowid 			= $rs->fields['rowid'];
	$objh->picturefilename	= $rs->fields['heinv_id'];


	/* ambil data adjustment price */
	$sql = " SELECT TOP 1 heinvpriceadj_value, heinvpriceadj_line
				FROM dbo.master_heinvpriceadj A
				WHERE
				heinv_id = '".$objh->heinv_id."'
				AND convert(varchar(10),A.heinvpriceadj_date,120)<=convert(varchar(10),getdate(),120)
				order by A.heinvpriceadj_date desc ";
	$rs  = $conn->Execute($sql);
	$heinvpriceadj_value = (float)  $rs->fields['heinvpriceadj_value'];
	$objh->heinv_pricegross = (float) ($objh->heinv_priceori + $heinvpriceadj_value);
	$objh->heinv_priceadjline = (int) $rs->fields['heinvpriceadj_line'];



	/* DetilItem */
	$sql = "select * from master_heinvitem where heinv_id='$id' ";
	$rs  = $conn->Execute($sql);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinvitem_line = $rs->fields['heinvitem_line'];
		$obj->heinvitem_size = $rs->fields['heinvitem_size'];
		$obj->heinvitem_barcode = $rs->fields['heinvitem_barcode'];

		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilItem'] = $arrdata;




	/* DetilSize */
	$sql = "select * from master_heinvsizetag where region_id='".$objh->region_id."'";

	$rs  = $conn->Execute($sql);
	$arrdata = array();
	while (!$rs->EOF) {
		$line++;
		unset($obj);
		$obj->heinv_id = $objh->heinv_id;
		$obj->heinvsizetag_line = 1*$rs->fields['SIZETAG'];
		$obj->heinvsizetag_descr = $rs->fields['DESCR'];
		$obj->C01 = $rs->fields['C01'];
		$obj->C02 = $rs->fields['C02'];
		$obj->C03 = $rs->fields['C03'];
		$obj->C04 = $rs->fields['C04'];
		$obj->C05 = $rs->fields['C05'];
		$obj->C06 = $rs->fields['C06'];
		$obj->C07 = $rs->fields['C07'];
		$obj->C08 = $rs->fields['C08'];
		$obj->C09 = $rs->fields['C09'];
		$obj->C10 = $rs->fields['C10'];
		$obj->C11 = $rs->fields['C11'];
		$obj->C12 = $rs->fields['C12'];
		$obj->C13 = $rs->fields['C13'];
		$obj->C14 = $rs->fields['C14'];
		$obj->C15 = $rs->fields['C15'];
		$obj->C16 = $rs->fields['C16'];
		$obj->C17 = $rs->fields['C17'];
		$obj->C18 = $rs->fields['C18'];
		$obj->C19 = $rs->fields['C19'];
		$obj->C20 = $rs->fields['C20'];
		$obj->C21 = $rs->fields['C21'];
		$obj->C22 = $rs->fields['C22'];
		$obj->C23 = $rs->fields['C23'];
		$obj->C24 = $rs->fields['C24'];
		$obj->C25 = $rs->fields['C25'];

		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilSize'] = $arrdata;


	/* DetilInventoryLog */
	$sql = "select * from master_heinvitem where heinv_id='xxx' ";
	$rs  = $conn->Execute($sql);
	$arrdata = array();
	$line = 0;
	while (!$rs->EOF) {
		unset($obj);
		$line = $line + 10;
		$obj->heinv_id 			= $rs->fields['heinv_id'];
		$obj->heinvsaldo_line 	= $line; //$rs->fields['heinvsaldo_line'];
		$obj->heinvsaldo_beg 	= (float) $rs->fields['heinvsaldo_beg'];
		$obj->heinvsaldo_rv 	= (float) $rs->fields['heinvsaldo_rv'];
		$obj->heinvsaldo_sl 	= (float) $rs->fields['heinvsaldo_sl'];
		$obj->heinvsaldo_aj 	= (float) $rs->fields['heinvsaldo_aj'];
		$obj->heinvsaldo_end 	= (float) $rs->fields['heinvsaldo_end'];
		$obj->heinvsaldo_cogs 	= (float) $rs->fields['heinvsaldo_cogs'];
		$obj->heinvsaldo_value 	= (float) $rs->fields['heinvsaldo_value'];

		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilInventoryLog'] = $arrdata;


	/* DetilPricingLog */
	$sql = "select * from master_heinvpricelog where heinv_id='$id' ";
	$rs  = $conn->Execute($sql);
	$arrdata = array();
	while (!$rs->EOF) {
		$line++;
		unset($obj);
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinvpricelog_line = 1*$rs->fields['heinvpricelog_line'];
		$obj->heinvpricelog_gendate = $rs->fields['heinvpricelog_gendate'];
		$obj->heinvpricelog_batch = $rs->fields['heinvpricelog_batch'];
		$obj->heinvpricelog_batchdate = $rs->fields['heinvpricelog_batchdate'];
		$obj->heinv_lastprice = (float) $rs->fields['heinv_lastprice'];
		$obj->heinv_lastdisc = (float) $rs->fields['heinv_lastdisc'];
		$obj->heinv_newprice = (float) $rs->fields['heinv_newprice'];
		$obj->heinv_newdisc = (float) $rs->fields['heinv_newdisc'];
		$obj->heinv_issp = 1*$rs->fields['heinv_issp'];
		$obj->heinv_pricingslot = $rs->fields['heinv_pricingslot'];
		$obj->heinvprice_id = $rs->fields['heinvprice_id'];

		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilPricingLog'] = $arrdata;


	/* DetilPricingAdj */
	$sql = "select * from master_heinvpriceadj where heinv_id='$id' ";
	$rs  = $conn->Execute($sql);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinvpriceadj_line = $rs->fields['heinvpriceadj_line'];
		$obj->heinvpriceadj_date = $rs->fields['heinvpriceadj_date'];
		$obj->heinvpriceadj_value = (float) $rs->fields['heinvpriceadj_value'];
		$obj->pricing_id = $rs->fields['pricing_id'];

		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilPricingAdj'] = $arrdata;



	/* Look Up Data Header */
	$sql = "select season_name from master_season where season_id='".$objh->season_id."'";
	$rs  = $conn->Execute($sql);
	$objh->season_name = $rs->fields['season_name'];


	$sql = "select heinvgro_name from master_heinvgro where heinvgro_id='".$objh->heinvgro_id."'";
	$rs  = $conn->Execute($sql);
	$objh->heinvgro_name = $rs->fields['heinvgro_name'];


	$sql = "select heinvctg_name, heinvctg_sizetag from master_heinvctg where heinvctg_id='".$objh->heinvctg_id."' and region_id = '".$objh->region_id."'";
	$rs  = $conn->Execute($sql);
	$objh->heinvctg_name = $rs->fields['heinvctg_name'];
	$objh->heinvctg_sizetag = $rs->fields['heinvctg_sizetag'];


	$data[0]['H'] = $objh;


/* ====================================================================================================== */




$sql = "select * from transaksi_tprop where id='$id'";
$rs  = $conn->Execute($sql);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->id = trim($rs->fields['id']);
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
$sql = "select * from transaksi_tlog where id='$id'  ";
$rs  = $conn->Execute($sql);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->id = trim($rs->fields['id']);
	$obj->log_line =  $i; //$rs->fields['log_line'];
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
$data[0]['D']['Log'] = $arrdata;




$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors);

print(stripslashes(json_encode($objResult)));

?>
