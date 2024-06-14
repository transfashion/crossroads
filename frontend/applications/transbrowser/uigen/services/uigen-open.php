<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}
	
	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	$sql = "select * from transbrowser_uigen where uigen_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->uigen_id = $rs->fields['uigen_id'];
	$objh->uigen_name = $rs->fields['uigen_name'];
	$objh->uigen_text = $rs->fields['uigen_text'];
	$objh->uigen_descr = $rs->fields['uigen_descr'];
	$objh->uigen_type = $rs->fields['uigen_type'];
	$objh->uigen_header = $rs->fields['uigen_header'];
	$objh->uigen_namespace = $rs->fields['uigen_namespace'];
	$objh->uigen_objectname = $rs->fields['uigen_objectname'];
	$objh->uigen_dll = $rs->fields['uigen_dll'];
	$objh->uigen_issingleinstance = (int) $rs->fields['uigen_issingleinstance'];
	$objh->uigen_islocaldb = (int) $rs->fields['uigen_islocaldb'];
	$objh->uigen_wsns = $rs->fields['uigen_wsns'];
	$objh->uigen_wsobject = $rs->fields['uigen_wsobject'];
	$objh->uigen_dataheadertable = $rs->fields['uigen_dataheadertable'];
	$objh->uigen_dataheaderfpk = $rs->fields['uigen_dataheaderfpk'];
	$objh->uigen_dataheaderfcb = $rs->fields['uigen_dataheaderfcb'];
	$objh->uigen_dataheaderfcd = $rs->fields['uigen_dataheaderfcd'];
	$objh->uigen_dataheaderfmb = $rs->fields['uigen_dataheaderfmb'];
	$objh->uigen_dataheaderfmd = $rs->fields['uigen_dataheaderfmd'];
	$objh->uigen_datadetil1use = (int) $rs->fields['uigen_datadetil1use'];
	$objh->uigen_datadetil1name = $rs->fields['uigen_datadetil1name'];
	$objh->uigen_datadetil1table = $rs->fields['uigen_datadetil1table'];
	$objh->uigen_datadetil1fpk1 = $rs->fields['uigen_datadetil1fpk1'];
	$objh->uigen_datadetil1fpk2 = $rs->fields['uigen_datadetil1fpk2'];
	$objh->uigen_datadetil1text = $rs->fields['uigen_datadetil1text'];
	$objh->uigen_datadetil2use = (int) $rs->fields['uigen_datadetil2use'];
	$objh->uigen_datadetil2name = $rs->fields['uigen_datadetil2name'];
	$objh->uigen_datadetil2table = $rs->fields['uigen_datadetil2table'];
	$objh->uigen_datadetil2fpk1 = $rs->fields['uigen_datadetil2fpk1'];
	$objh->uigen_datadetil2fpk2 = $rs->fields['uigen_datadetil2fpk2'];
	$objh->uigen_datadetil2text = $rs->fields['uigen_datadetil2text'];
	$objh->uigen_datadetil3use = (int) $rs->fields['uigen_datadetil3use'];
	$objh->uigen_datadetil3name = $rs->fields['uigen_datadetil3name'];
	$objh->uigen_datadetil3table = $rs->fields['uigen_datadetil3table'];
	$objh->uigen_datadetil3fpk1 = $rs->fields['uigen_datadetil3fpk1'];
	$objh->uigen_datadetil3fpk2 = $rs->fields['uigen_datadetil3fpk2'];
	$objh->uigen_datadetil3text = $rs->fields['uigen_datadetil3text'];
	$objh->uigen_datadetil4use = (int) $rs->fields['uigen_datadetil4use'];
	$objh->uigen_datadetil4name = $rs->fields['uigen_datadetil4name'];
	$objh->uigen_datadetil4table = $rs->fields['uigen_datadetil4table'];
	$objh->uigen_datadetil4fpk1 = $rs->fields['uigen_datadetil4fpk1'];
	$objh->uigen_datadetil4fpk2 = $rs->fields['uigen_datadetil4fpk2'];
	$objh->uigen_datadetil4text = $rs->fields['uigen_datadetil4text'];
	$objh->uigen_datadetil5use = (int) $rs->fields['uigen_datadetil5use'];
	$objh->uigen_datadetil5name = $rs->fields['uigen_datadetil5name'];
	$objh->uigen_datadetil5table = $rs->fields['uigen_datadetil5table'];
	$objh->uigen_datadetil5fpk1 = $rs->fields['uigen_datadetil5fpk1'];
	$objh->uigen_datadetil5fpk2 = $rs->fields['uigen_datadetil5fpk2'];
	$objh->uigen_datadetil5text = $rs->fields['uigen_datadetil5text'];
	$objh->uigen_createby = $rs->fields['uigen_createby'];
	$objh->uigen_createdate = trim($rs->fields['uigen_createdate']);
	$objh->uigen_modifyby = $rs->fields['uigen_modifyby'];
	$objh->uigen_modifydate = trim($rs->fields['uigen_modifydate']);
	$objh->rowid = $rs->fields['rowid'];
	
	
	$data[0]['H'] = $objh;
	

	
	$datadetil = array('DetilH', 'Detil1', 'Detil2', 'Detil3', 'Detil4', 'Detil5');
	foreach ($datadetil as $DATADETILNAME) {
		$sql = "select * from transbrowser_uigendetil where uigen_id='$id' AND uigen_datadetilname='$DATADETILNAME'";
		$rs  = $conn->Execute($sql);
		unset($arrdata);
		$arrdata = array();
		while (!$rs->EOF) {
			unset($obj);
			$obj->uigen_id = $rs->fields['uigen_id'];
			$obj->uigen_datadetilname 	= $rs->fields['uigen_datadetilname'];
			$obj->uigendetil_line 		= (int) $rs->fields['uigendetil_line'];
			$obj->uigendetil_seq 		= (int) $rs->fields['uigendetil_seq'];
			$obj->uigendetil_name 		= $rs->fields['uigendetil_name'];
			$obj->uigendetil_text 		= $rs->fields['uigendetil_text'];
			$obj->uigendetil_datatype 	= $rs->fields['uigendetil_datatype'];
			$obj->uigendetil_datalen 	= (int) $rs->fields['uigendetil_datalen'];
			$obj->uigendetil_dataprec 	= (int) $rs->fields['uigendetil_dataprec'];
			$obj->uigendetil_isgenerate = (int) $rs->fields['uigendetil_isgenerate'];
			$obj->uigendetil_type 		= $rs->fields['uigendetil_type'];
			$obj->uigendetil_objectwidth = (int) $rs->fields['uigendetil_objectwidth'];
			$obj->uigendetil_objectcolor = $rs->fields['uigendetil_objectcolor'];
			$obj->uigendetil_islisted 	= (int) $rs->fields['uigendetil_islisted'];
			$obj->uigendetil_issearch 	= (int) $rs->fields['uigendetil_issearch'];
			$obj->uigendetil_isvisible 	= (int) $rs->fields['uigendetil_isvisible'];
			$obj->uigendetil_isenabled 	= (int) $rs->fields['uigendetil_isenabled'];
			$obj->uigendetil_textalign 	= $rs->fields['uigendetil_textalign'];
			
			$arrdata[] = $obj;
			$rs->MoveNext();
		}
		$data[0]['D'][$DATADETILNAME] = $arrdata;
	}
	
	
	
	
	
	

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