<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --
    created by   dwi.atno
    created date 04/05/2011 15:56
inv tester
Filename: uimstctg-open.php
*/



if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	set_time_limit(100);


	$sql = "select * from master_heinvctg where heinvctg_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->heinvctg_id = trim($rs->fields['heinvctg_id']);
	$objh->heinvctg_extctg = trim($rs->fields['heinvctg_extctg']);
	$objh->heinvctg_extgro = trim($rs->fields['heinvctg_extgro']);
	$objh->heinvctg_seqcode = trim($rs->fields['heinvctg_seqcode']);
	$objh->heinvctg_name = trim($rs->fields['heinvctg_name']);
	$objh->heinvctg_namegroup = trim($rs->fields['heinvctg_namegroup']);
	$objh->heinvctg_descr = trim($rs->fields['heinvctg_descr']);
	$objh->heinvctg_class = trim($rs->fields['heinvctg_class']);
	$objh->heinvctg_gender = trim($rs->fields['heinvctg_gender']);
	$objh->heinvctg_sizetag = trim($rs->fields['heinvctg_sizetag']);
	$objh->heinvctg_isdisabled = trim($rs->fields['heinvctg_isdisabled']);
	$objh->heinvctg_createby = trim($rs->fields['heinvctg_createby']);
	$objh->heinvctg_createdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heinvctg_createdate']));
	$objh->heinvctg_modifyby = trim($rs->fields['heinvctg_modifyby']);
	$objh->heinvctg_modifydate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heinvctg_modifydate']));
	$objh->heinvgro_id = trim($rs->fields['heinvgro_id']);
	$objh->heinvlogisticgroup_id = trim($rs->fields['heinvlogisticgroup_id']);


	
	$heinvgro_id = trim($rs->fields['heinvgro_id']);
	$SQL ="select heinvgro_name from master_heinvgro WHERE heinvgro_id = '$heinvgro_id'";
	$RSGRONAME = $conn->execute($SQL);
	$objh->heinvgro_name = trim($RSGRONAME->fields['heinvgro_name']);
	

	
	$objh->region_id = trim($rs->fields['region_id']);
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
