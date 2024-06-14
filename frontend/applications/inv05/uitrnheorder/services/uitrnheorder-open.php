<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	set_time_limit(100);


	$sql = "select * from transaksi_heorder where heorder_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->heorder_id = $rs->fields['heorder_id'];
	$objh->heorder_idext = $rs->fields['heorder_idext'];
	$objh->heorder_source = $rs->fields['heorder_source'];
	$objh->heorder_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_date']));
	$objh->heorder_dateexp = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_dateexp']));
	$objh->heorder_descr = $rs->fields['heorder_descr'];
	$objh->heorder_isdisabled = $rs->fields['heorder_isdisabled'];
	$objh->heorder_isposted = $rs->fields['heorder_isposted'];
	$objh->heorder_isclosed = $rs->fields['heorder_isclosed'];
	$objh->heorder_createby = $rs->fields['heorder_createby'];
	$objh->heorder_createdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_createdate']));
	$objh->heorder_modifyby = $rs->fields['heorder_modifyby'];
	$objh->heorder_modifydate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_modifydate']));
	$objh->region_id = $rs->fields['region_id'];
	$objh->rekanan_id = $rs->fields['rekanan_id'];
	$objh->season_id = $rs->fields['season_id'];
	$objh->currency_id = $rs->fields['currency_id'];
	$objh->channel_id = $rs->fields['channel_id'];
	$objh->rowid = $rs->fields['rowid'];




	$sql  = "select A.*, heinvgro_id=B.heinvgro_id, heinvctg_id=B.heinvctg_id, heinv_sizetag=(SELECT heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id=B.heinvctg_id) ";
	$sql .= "from ";
	$sql .= "transaksi_heorderdetil A left join master_heinv B on A.heinv_id=B.heinv_id ";
	$sql .= "where heorder_id='$id'";

	$rs  = $conn->Execute($sql);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->heorder_id = $rs->fields['heorder_id'];
		$obj->heorderdetil_line = $rs->fields['heorderdetil_line'];
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinv_art = $rs->fields['heinv_art'];
		$obj->heinv_mat = $rs->fields['heinv_mat'];
		$obj->heinv_col = $rs->fields['heinv_col'];
		$obj->heinv_name = $rs->fields['heinv_name'];
		
		$obj->heinvgro_id = $rs->fields['heinvgro_id'];
		//$obj->heinvctg_id = $rs->fields['heinvctg_id'];
		
		
		$sql = "SELECT heinvctg_name FROM master_heinvctg WHERE heinvctg_id = '".$rs->fields['heinvctg_id']."'";
		$rsC = $conn->Execute($sql);
		$obj->heinvctg_id = $rsC->fields['heinvctg_name'];
		
		
		$obj->heinv_sizetag = $rs->fields['heinv_sizetag'];

		$obj->heinv_price = (float) $rs->fields['heinv_price'];
		
		
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
		$obj->heinv_pricesubtotal = 1*$qty*$obj->heinv_price;
		
		$obj->heinv_received = 0;
		$obj->heinv_outstanding = 1*($obj->heinv_qty - $obj->heinv_received);
		
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilItem'] = $arrdata;
	
	
	
	
	
	$sql  = "SELECT DISTINCT ";
	$sql .= "A.hemoving_id, A.hemoving_descr, A.hemoving_isrecv, A.hemoving_createby, A.hemoving_createdate ";
	$sql .= "FROM transaksi_hemoving A inner join transaksi_hemovingdetil B ";
    $sql .= " on A.hemoving_id = B.hemoving_id ";
	$sql .= "WHERE B.ref_id='$id' ";
	
	$rs  = $conn->Execute($sql);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
		
		$obj->id = $rs->fields['hemoving_id'];
		$obj->descr = $rs->fields['hemoving_descr'];
		$obj->isposted = $rs->fields['hemoving_isrecv'];
		$obj->createby = $rs->fields['hemoving_createby'];
		$obj->createdate = $rs->fields['hemoving_createdate'];
		
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	
	$data[0]['D']['DetilResponse'] = $arrdata;
	
	
	

	
	
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