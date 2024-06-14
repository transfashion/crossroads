<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}
	
	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	$sql = "select * from transaksi_hepos where bon_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->bon_id = $rs->fields['bon_id'];
	$objh->bon_idext = $rs->fields['bon_idext'];
	$objh->bon_event = $rs->fields['bon_event'];
	$objh->bon_date = SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['bon_date'])); 
	$objh->bon_createby = $rs->fields['bon_createby'];
	$objh->bon_createdate = trim($rs->fields['bon_createdate']);
	$objh->bon_modifyby = $rs->fields['bon_modifyby'];
	$objh->bon_modifydate = trim($rs->fields['bon_modifydate']);
	$objh->bon_isvoid = $rs->fields['bon_isvoid'];
	$objh->bon_voidby = $rs->fields['bon_voidby'];
	$objh->bon_voiddate = trim($rs->fields['bon_voiddate']);
	$objh->bon_replacefromvoid = $rs->fields['bon_replacefromvoid'];
	$objh->bon_msubtotal = (float) $rs->fields['bon_msubtotal'];
	$objh->bon_msubtvoucher = (float) $rs->fields['bon_msubtvoucher'];
	$objh->bon_msubtdiscadd = (float) $rs->fields['bon_msubtdiscadd'];
	$objh->bon_msubtredeem = (float) $rs->fields['bon_msubtredeem'];
	$objh->bon_msubtracttotal = (float) $rs->fields['bon_msubtracttotal'];
	$objh->bon_msubtotaltobedisc = (float) $rs->fields['bon_msubtotaltobedisc'];
	$objh->bon_mdiscpaympercent = (float) $rs->fields['bon_mdiscpaympercent'];
	$objh->bon_mdiscpayment = (float) $rs->fields['bon_mdiscpayment'];
	$objh->bon_mtotal = (float) $rs->fields['bon_mtotal'];
	$objh->bon_mpayment = (float) $rs->fields['bon_mpayment'];
	$objh->bon_mrefund = (float) $rs->fields['bon_mrefund'];
	$objh->bon_msalegross = (float) $rs->fields['bon_msalegross'];
	$objh->bon_msaletax = (float) $rs->fields['bon_msaletax'];
	$objh->bon_msalenet = (float) $rs->fields['bon_msalenet'];
	$objh->bon_itemqty = (int) $rs->fields['bon_itemqty'];
	$objh->bon_rowitem = (int) $rs->fields['bon_rowitem'];
	$objh->bon_rowpayment = (int) $rs->fields['bon_rowpayment'];
	$objh->bon_npwp = $rs->fields['bon_npwp'];
	$objh->bon_fakturpajak = $rs->fields['bon_fakturpajak'];
	$objh->bon_adddisc_authusername = $rs->fields['bon_adddisc_authusername'];
	$objh->bon_disctype = $rs->fields['bon_disctype'];
	$objh->customer_id = $rs->fields['customer_id'];
	$objh->customer_name = $rs->fields['customer_name'];
	$objh->customer_telp = $rs->fields['customer_telp'];
	$objh->customer_npwp = $rs->fields['customer_npwp'];
	$objh->customer_ageid = $rs->fields['customer_ageid'];
	$objh->customer_agename = $rs->fields['customer_agename'];
	$objh->customer_genderid = $rs->fields['customer_genderid'];
	$objh->customer_gendername = $rs->fields['customer_gendername'];
	$objh->customer_nationalityid = $rs->fields['customer_nationalityid'];
	$objh->customer_nationalityname = $rs->fields['customer_nationalityname'];
	$objh->customer_typename = $rs->fields['customer_typename'];
	$objh->customer_passport = $rs->fields['customer_passport'];
	$objh->customer_disc = (int) $rs->fields['customer_disc'];
	$objh->voucher01_id = $rs->fields['voucher01_id'];
	$objh->voucher01_name = $rs->fields['voucher01_name'];
	$objh->voucher01_codenum = $rs->fields['voucher01_codenum'];
	$objh->voucher01_method = $rs->fields['voucher01_method'];
	$objh->voucher01_type = $rs->fields['voucher01_type'];
	$objh->voucher01_discp = (int) $rs->fields['voucher01_discp'];
	$objh->salesperson_id = $rs->fields['salesperson_id'];
	$objh->salesperson_name = $rs->fields['salesperson_name'];
	$objh->pospayment_id = $rs->fields['pospayment_id'];
	$objh->pospayment_name = $rs->fields['pospayment_name'];
	$objh->posedc_id = $rs->fields['posedc_id'];
	$objh->posedc_name = $rs->fields['posedc_name'];
	$objh->machine_id = $rs->fields['machine_id'];
	$objh->region_id = $rs->fields['region_id'];
	$objh->branch_id = $rs->fields['branch_id'];
	$objh->syncode = $rs->fields['syncode'];
	$objh->syndate = trim($rs->fields['syndate']);
	$objh->rowid = $rs->fields['rowid'];

	/* lookup region, branch */
	$sql = "select region_name from master_region where region_id = '".$objh->region_id."' ";
	$rsI = $conn->Execute($sql);
	$objh->region_name = $rsI->fields['region_name'];

	$sql = "select branch_name from master_branch where branch_id = '".$objh->branch_id."' ";
	$rsI = $conn->Execute($sql);	
	$objh->branch_name = $rsI->fields['branch_name'];	
	
	/* cek periode */
	$closingstatus_id = substr($rs->fields['bon_date'], 0, 4) . substr($rs->fields['bon_date'], 5, 2) . "-" .  $rs->fields['region_id'];
	$SQL = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$closingstatus_id' AND heinvclosingstatus_iscompleted=1";		
	$rs  = $conn->Execute($SQL);
	if ($rs->recordCount()) {
		$objh->periode_isclosed = 1;
	} else {
		$objh->periode_isclosed = 0;	
	} 
	
	
	$data[0]['H'] = $objh;
	

	
	$sql = "select * from transaksi_heposdetil where bon_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->bon_id = $rs->fields['bon_id'];
		$obj->bondetil_line = $rs->fields['bondetil_line'];
		$obj->bondetil_gro = $rs->fields['bondetil_gro'];
		$obj->bondetil_ctg = $rs->fields['bondetil_ctg'];
		$obj->bondetil_art = $rs->fields['bondetil_art'];
		$obj->bondetil_mat = $rs->fields['bondetil_mat'];
		$obj->bondetil_col = $rs->fields['bondetil_col'];
		$obj->bondetil_size = $rs->fields['bondetil_size'];
		$obj->bondetil_descr = $rs->fields['bondetil_descr'];
		$obj->bondetil_qty = (int) $rs->fields['bondetil_qty'];
		$obj->bondetil_mpricegross = (float) $rs->fields['bondetil_mpricegross'];
		$obj->bondetil_mdiscpstd01 = (int) $rs->fields['bondetil_mdiscpstd01'];
		$obj->bondetil_mdiscrstd01 = (float) $rs->fields['bondetil_mdiscrstd01'];
		$obj->bondetil_mpricenettstd01 = (float) $rs->fields['bondetil_mpricenettstd01'];
		$obj->bondetil_mdiscpvou01 = (int) $rs->fields['bondetil_mdiscpvou01'];
		$obj->bondetil_mdiscrvou01 = (float) $rs->fields['bondetil_mdiscrvou01'];
		$obj->bondetil_mpricecettvou01 = (float) $rs->fields['bondetil_mpricecettvou01'];
		$obj->bondetil_vou01id = $rs->fields['bondetil_vou01id'];
		$obj->bondetil_vou01codenum = $rs->fields['bondetil_vou01codenum'];
		$obj->bondetil_vou01type = $rs->fields['bondetil_vou01type'];
		$obj->bondetil_vou01method = $rs->fields['bondetil_vou01method'];
		$obj->bondetil_vou01discp = (int) $rs->fields['bondetil_vou01discp'];
		$obj->bondetil_mpricenett = (float) $rs->fields['bondetil_mpricenett'];
		$obj->bondetil_msubtotal = (float) $rs->fields['bondetil_msubtotal'];
		$obj->bondetil_rule = $rs->fields['bondetil_rule'];
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinvitem_id = $rs->fields['heinvitem_id'];
		$obj->heinvitem_barcode = $rs->fields['heinvitem_barcode'];
		$obj->region_id = $rs->fields['region_id'];
		$obj->region_nameshort = $rs->fields['region_nameshort'];
		$obj->colname = $rs->fields['colname'];
		$obj->sizetag = $rs->fields['sizetag'];
		$obj->proc = $rs->fields['proc'];
		$obj->bon_idext = $rs->fields['bon_idext'];
		
		/* lookup price ori */
		/*
		$sql = "select heinv_priceori from master_heinv where heinv_id='".$obj->heinv_id."' ";
		$rsI = $conn->Execute($sql);
		$obj->heinv_priceori = (float) $rsI->fields['heinv_priceori'];
		*/
		$sql = "
		select heinv_priceori = heinv_priceori + isnull((select top 1 heinvpriceadj_value from master_heinvpriceadj 
		where heinv_id=A.heinv_id
		      and heinvpriceadj_date<= (select bon_date from transaksi_hepos where bon_id = '".$obj->bon_id."')
		 order by heinvpriceadj_date desc), 0) from master_heinv A
		where heinv_id='".$obj->heinv_id."'		
		";
		$rsI = $conn->Execute($sql);
		$obj->heinv_priceori = (float) $rsI->fields['heinv_priceori'];		
		
		
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilItem'] = $arrdata;
	
	
	
	$sql = "select * from transaksi_hepospayment where bon_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->bon_id = $rs->fields['bon_id'];
		$obj->payment_line = $rs->fields['payment_line'];
		$obj->payment_cardnumber = $rs->fields['payment_cardnumber'];
		$obj->payment_cardholder = $rs->fields['payment_cardholder'];
		$obj->payment_mvalue = (float) $rs->fields['payment_mvalue'];
		$obj->payment_mcash = (float) $rs->fields['payment_mcash'];
		$obj->payment_installment = (int) $rs->fields['payment_installment'];
		$obj->pospayment_id = $rs->fields['pospayment_id'];
		$obj->pospayment_name = $rs->fields['pospayment_name'];
		$obj->pospayment_bank = $rs->fields['pospayment_bank'];
		$obj->posedc_id = $rs->fields['posedc_id'];
		$obj->posedc_name = $rs->fields['posedc_name'];
		$obj->bon_idext = $rs->fields['bon_idext'];
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilPayment'] = $arrdata;
	

	
	

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