<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --
    created by   fakhri.reza
    created date 21/05/2012 13:21
POS Sales Person
Filename: uimstsalesperson-open.php
*/



if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	set_time_limit(100);


	$sql = "select * from master_possalesperson where possalesperson_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->possalesperson_id = trim($rs->fields['possalesperson_id']);
	$objh->possalesperson_name = trim($rs->fields['possalesperson_name']);
	$objh->possalesperson_isdisabled = trim($rs->fields['possalesperson_isdisabled']);
	$objh->region_id = trim($rs->fields['region_id']);
	$objh->nik = trim($rs->fields['nik']);
	$objh->possalesperson_createby = trim($rs->fields['possalesperson_createby']);
	$objh->possalesperson_createdate = trim($rs->fields['possalesperson_createdate']);
	$objh->possalesperson_modifyby = trim($rs->fields['possalesperson_modifyby']);
	$objh->possalesperson_modifydate = trim($rs->fields['possalesperson_modifydate']);
	$objh->rowid = trim($rs->fields['rowid']);



	
	
	
	
	
	
	
	

	/* Lookup data Header */
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
	$obj->log_id = trim($rs->fields['id']);
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