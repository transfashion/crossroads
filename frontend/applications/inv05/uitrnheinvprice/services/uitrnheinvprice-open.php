<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	unset($data);
	
	set_time_limit(100);

	$sql = "select * from transaksi_heinvprice where price_id='$id'";
	$rs  = $conn->Execute($sql);


	unset($objh);
	$objh->price_id = $rs->fields['price_id'];
	$objh->region_id = $rs->fields['region_id'];
	$objh->price_startdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_startdate']));
	$objh->price_enddate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_enddate']));
	$objh->pricingtype_id = $rs->fields['pricingtype_id'];
	$objh->price_descr = $rs->fields['price_descr'];
	$objh->price_isposted = $rs->fields['price_isposted'];
	$objh->price_isverified = $rs->fields['price_isverified'];
	$objh->price_isgenerated = $rs->fields['price_isgenerated'];
	$objh->price_isnewitemprice = $rs->fields['price_isnewitemprice'];
	$objh->heorder_id = $rs->fields['heorder_id'];

    $objh->project_id = $rs->fields['project_id'];
    $project_id = $rs->fields['project_id'];
    
    $sqlP = "SELECT project_name FROM transaksi_project WHERE project_id = '$project_id'";
    $rsP = $conn->execute($sqlP);
    $objh->project_name = $rsP->fields['project_name'];
    
	$objh->price_createby = $rs->fields['price_createby'];
	$objh->price_createdate = $rs->fields['price_createdate'];
	$objh->price_modifyby = $rs->fields['price_modifyby'];
	$objh->price_modifydate =  SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_modifydate']));
	$objh->price_postby = trim($rs->fields['price_postby']);
	$objh->price_postdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_postdate']));
	$objh->price_verifyby = trim($rs->fields['price_verifyby']);
	$objh->price_verifydate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['price_verifydate']));

	$objh->ref_id = trim($rs->fields['ref_id']);
	$objh->rowid = $rs->fields['rowid'];
	
	
	/* Look Up Data Header */
	$sql = "select ref_descr = heorder_descr from transaksi_heorder where heorder_id='".$rs->fields['ref_id']."'";
	$rs  = $conn->Execute($sql);
	$objh->ref_descr = $rs->fields['ref_descr'];
	
	$data[0]['H'] = $objh;


	$sql  = "SELECT * FROM transaksi_heinvpricedetil where price_id = '$id'";
	$rs  = $conn->Execute($sql);
	$arrdataItem = array();
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->price_id 				= $rs->fields['price_id'];
		$obj->pricedetil_line 		= $rs->fields['pricedetil_line'];
		$obj->heinv_id 				= $rs->fields['heinv_id'];
		$obj->heinv_art 			= $rs->fields['heinv_art'];
		$obj->heinv_mat 			= $rs->fields['heinv_mat'];
		$obj->heinv_col 			= $rs->fields['heinv_col'];
		$obj->heinv_name 			= $rs->fields['heinv_name'];
		$obj->heinvgro_id 			= $rs->fields['heinvgro_id'];
		$obj->heinvctg_id 			= $rs->fields['heinvctg_id'];
 		$obj->heinv_price01 		= 1*$rs->fields['heinv_price01'];
 		$obj->heinv_pricedisc01 	= 1*$rs->fields['heinv_pricedisc01'];
		$obj->heinv_isSP 			= 1*$rs->fields['heinv_isSP'];
		$obj->heinv_isadjgross 		= 1*$rs->fields['heinv_isadjgross'];
		$obj->ref_id 				= $rs->fields['ref_id'];		
		$obj->ref_line 				= 1*$rs->fields['ref_line'];

		$obj->heinv_price_hk 		= 1*$rs->fields['heinv_price_hk'];
		$obj->heinv_price_sin 		= 1*$rs->fields['heinv_price_sin'];

		$heinv_id  = trim($rs->fields['heinv_id']);
		$heinv_art  = trim($rs->fields['heinv_art']);
		$heinv_mat  = trim($rs->fields['heinv_mat']);
		$heinv_col  = trim($rs->fields['heinv_col']);
		$heinvgro_id  =  trim($rs->fields['heinvgro_id']);
		$heinvctg_id  = trim($rs->fields['heinvctg_id']);
		
		$sql_cek="SELECT heinv_id, heinv_art,heinv_mat,heinv_col,heinvgro_id, heinvctg_id,heinv_priceori,heinv_price01,heinv_pricedisc01 FROM master_heinv WHERE heinv_id='$heinv_id'";
		$rs_cek=$conn->execute($sql_cek);
			
		$heinv_id_cek = trim($rs_cek->fields['heinv_id']);
		$heinv_art_cek = trim($rs_cek->fields['heinv_art']);
		$heinv_mat_cek = trim($rs_cek->fields['heinv_mat']);
		$heinv_col_cek = trim($rs_cek->fields['heinv_col']);
		$heinvgro_id_cek = trim($rs_cek->fields['heinvgro_id']);
		$heinvctg_id_cek = trim($rs_cek->fields['heinvctg_id']);
		
		
		
		$heinv_firstprice = $rs_cek->fields['heinv_priceori'];
		$heinv_price01 = 1*$rs_cek->fields['heinv_price01'];
		$heinv_pricedisc01 = 1*$rs_cek->fields['heinv_pricedisc01'];
		
		
		$obj->heinv_lastprice 		= $heinv_price01;
 		$obj->heinv_lastdisc 		= $heinv_pricedisc01;
 		
 		$obj->heinv_firstprice 		= 1*$heinv_firstprice;


		$obj->isValid=1;
		IF (($heinv_id!=$heinv_id_cek))
		{
		 	$obj->isValid=0;
		 
		 }
		 
		IF (($heinv_art!=$heinv_art_cek))
		{
		 	$obj->isValid=0;
		 	 
		 }

		IF (($heinv_mat!=$heinv_mat_cek))
		{
		 	$obj->isValid=0;

		 }

		IF (($heinv_col!=$heinv_col_cek))
		{
		 	$obj->isValid=0;
		 			 	 		 	
		 }



		IF (($heinvgro_id!=$heinvgro_id_cek))
		{
		 	$obj->isValid=0;
		 
		 }



		IF (($heinvctg_id!=$heinvctg_id_cek))
		{
		 	$obj->isValid=0;

		 }
 
		//print " - ---------**".  $obj->heinv_qtyprop . " " . $obj->heinv_qtysend ." ". $obj->heinv_qtyrecv . " --------------\n";
		
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