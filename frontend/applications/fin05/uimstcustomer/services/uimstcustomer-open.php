<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}
	
	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	$sql = "select * from master_customer where customer_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->customer_id = trim($rs->fields['customer_id']);
	$objh->customer_title = trim($rs->fields['customer_title']);
	$objh->customer_namefull = trim($rs->fields['customer_namefull']);
	$objh->customer_namenick = trim($rs->fields['customer_namenick']);
	$objh->customer_address = trim($rs->fields['customer_address']);
	$objh->customer_city = trim($rs->fields['customer_city']);
	$objh->customer_postcode = trim($rs->fields['customer_postcode']);
	$objh->customer_provincy = trim($rs->fields['customer_provincy']);
	$objh->customer_country = trim($rs->fields['customer_country']);
	$objh->customer_phonehome = trim($rs->fields['customer_phonehome']);
	$objh->customer_phonework = trim($rs->fields['customer_phonework']);
	$objh->customer_email = trim($rs->fields['customer_email']);
	$objh->customer_sizetop = trim($rs->fields['customer_sizetop']);
	$objh->customer_sizebottom = trim($rs->fields['customer_sizebottom']);
	$objh->customer_sizeshoes = trim($rs->fields['customer_sizeshoes']);
	$objh->customer_createby = trim($rs->fields['customer_createby']);
	$objh->customer_createdate = SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['customer_createdate']));
	$objh->customer_modifyby = trim($rs->fields['customer_modifyby']);
	$objh->customer_modifydate = SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['customer_modifydate']));
	$objh->occupation_id = trim($rs->fields['occupation_id']);
	$objh->gender_id = trim($rs->fields['gender_id']);
	$objh->customertype_id = trim($rs->fields['customertype_id']);
	$objh->rekanan_id = trim($rs->fields['rekanan_id']);
	
	$objh->region_id = trim($rs->fields['region_id']);
	$objh->branch_id = trim($rs->fields['branch_id']);
	$objh->qty = 1*trim($rs->fields['qty']);
	$objh->buy = 1*trim($rs->fields['buy']);
	$objh->date =  SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['customer_createdate']));

	
	
	$objh->rowid = trim($rs->fields['rowid']);
	
	/* lookup data */
	$sql = sprintf("select rekanan_name from master_rekanan where rekanan_id = '%s'", $objh->rekanan_id);
	$rs = $conn->Execute($sql);
	$objh->rekanan_name = trim($rs->fields['rekanan_name']);
	
	$data[0]['H'] = $objh;
	
	
	/*
	$sql = "select * from master_region where region_parent='0'";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$region_id = $rs->fields['region_id'];
		$sql = "select * from master_customerregion where customer_id='$id' AND region_id='$region_id'";
		$rsR = $conn->Execute($sql);
		if ($rsR->recordCount()) {
			$obj->selected = 1;
		} else {
			$obj->selected = 0;
		}
		
		$obj->region_id = trim($rs->fields['region_id']);
		$obj->region_name = trim($rs->fields['region_name']);
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilRegion'] = $arrdata;
	
	
	
	$sql = "select * from master_customercontact where customer_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->customer_id = trim($rs->fields['customer_id']);
		$obj->customercontact_line = trim($rs->fields['customercontact_line']);
		$obj->customercontact_name = trim($rs->fields['customercontact_name']);
		$obj->customercontact_address = trim($rs->fields['customercontact_address']);
		$obj->customercontact_phone = trim($rs->fields['customercontact_phone']);
		$obj->customercontact_email = trim($rs->fields['customercontact_email']);
		$obj->customercontact_position = trim($rs->fields['customercontact_position']);
		$obj->customercontact_primary = trim($rs->fields['customercontact_primary']);
		$obj->rowid = trim($rs->fields['rowid']);
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilContact'] = $arrdata;
	
	
	
	$sql = "select * from master_customerbank where customer_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->customer_id = trim($rs->fields['customer_id']);
		$obj->customerbank_line = trim($rs->fields['customerbank_line']);
		$obj->customerbank_name = trim($rs->fields['customerbank_name']);
		$obj->customerbank_account = trim($rs->fields['customerbank_account']);
		$obj->bank_id = trim($rs->fields['bank_id']);
		$obj->rowid = trim($rs->fields['rowid']);
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilBank'] = $arrdata;
	*/	

	
	
	$sql = "select * from master_customerprop where prop_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->prop_id = trim($rs->fields['prop_id']);
		$obj->prop_line = $rs->fields['prop_line'];
		$obj->prop_name = $rs->fields['prop_name'];
		$obj->prop_descr = $rs->fields['prop_descr'];
		$obj->prop_value = $rs->fields['prop_value'];
		$obj->rowid = $rs->fields['rowid'];
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['Prop'] = $arrdata;
	
	
	
	/*
	log nanti dibatasi cuma 30 data terakhir 
	*/
	$sql = "select TOP 30 * FROM master_customerlog where log_id='$id' ORDER BY log_date DESC";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->log_id = trim($rs->fields['log_id']);
		$obj->log_line = $rs->fields['log_line'];
		$obj->log_date = SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['log_date']));
		$obj->log_action = $rs->fields['log_action'];
		$obj->log_descr = $rs->fields['log_descr'];
		$obj->log_table = $rs->fields['log_table'];
		$obj->log_lastvalue = $rs->fields['log_lastvalue'];
		$obj->log_username = $rs->fields['log_username'];
		$obj->rowid = $rs->fields['rowid'];
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['Log'] = $arrdata;
	
	
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>