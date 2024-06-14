<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];
$doc        = $_GET['doc'];


$sqlH = "select 
A.price_id, 
A.region_id,
A.price_startdate,
A.price_descr,
A.price_createby,
B.pricedetil_line, 
B.heinv_id,
B.heinv_art,
season_id = (select season_id FROM master_heinv WHERE heinv_id = B.heinv_id),
B.heinv_mat, 
B.heinv_col, 
B.heinv_name, 
B.heinv_lastprice,
B.heinv_lastdisc,
B.heinv_price01,
B.heinv_pricedisc01,
B.heinv_isSP,
B.heinv_isadjgross
from transaksi_heinvprice A inner join transaksi_heinvpricedetil B on
A.price_id = B.price_id WHERE A.price_id = '$id'";

$data = array();
$rs = $conn->Execute($sqlH);

$region_id = $rs->fields['region_id'];
 



$sqlr="SELECT region_name FROM master_region WHERE region_id = '$region_id'";
$rsb=$conn->Execute($sqlr);

$region_name = $rsb->fields['region_name'];

while (!$rs->EOF) {
 
 	unset($obj);
 

	$obj->price_id 					= $rs->fields['price_id'];
	$obj->region_id 				= $rs->fields['region_id'];
	$obj->region_name 				= $region_name;
	$obj->pricedetil_line 			= $rs->fields['pricedetil_line'];
	$obj->price_startdate 			= $rs->fields['price_startdate'];
	$obj->price_descr	 			= $rs->fields['price_descr'];
	$obj->price_createby	 		= $rs->fields['price_createby'];
	$obj->pricedetil_line	 		= 1*$rs->fields['pricedetil_line'];
	
	$obj->heinv_id 					= $rs->fields['heinv_id'];
	$obj->heinv_art 				= $rs->fields['heinv_art'] . ' (' . $rs->fields['season_id'] . ')';

//	$obj->heinv_art 				= $rs->fields['heinv_art'];
	$obj->heinv_mat 				= $rs->fields['heinv_mat'];
 	$obj->heinv_col 				= $rs->fields['heinv_col'];
 	$obj->heinv_name 				= $rs->fields['heinv_name'];
 	
 	$heinv_id = $rs->fields['heinv_id'];
 	
 	$SQLI = "SELECT * FROM master_heinv WHERE heinv_id = '$heinv_id'";
 	$rsI = $conn->execute($SQLI);
 	
 	$heinvgro_id					= $rsI->fields['heinvgro_id'];
 	$heinvctg_id					= $rsI->fields['heinvctg_id'];
 	$region_id						= $rsI->fields['region_id'];
 	
 	$SQLGRO = "SELECT heinvgro_name FROM master_heinvgro WHERE heinvgro_id = '$heinvgro_id' and region_id = '$region_id'";
 	$rsGro = $conn->execute($SQLGRO);
 	$obj->heinvgro_id 				= $heinvgro_id;
 	$obj->heinvgro_name				= $rsGro->fields['heinvgro_name'];
 	

 	$SQLCTG = "SELECT heinvctg_name FROM master_heinvctg WHERE heinvgro_id = '$heinvgro_id' and heinvctg_id = '$heinvctg_id' AND region_id = '$region_id'";
 	$rsCtg = $conn->execute($SQLCTG);
 	$obj->heinvctg_id 				= $heinvctg_id; 	
 	$obj->heinvctg_name				= $rsCtg->fields['heinvctg_name']; 	
 	
 	
 	
	$obj->heinv_lastprice 			= ' ' . is_numeric($rs->fields['heinv_lastprice']) ? number_format($rs->fields['heinv_lastprice']) : $rs->fields['heinv_lastprice'];
	$obj->heinv_lastdisc	 		= ' ' . is_numeric($rs->fields['heinv_lastdisc']) ? number_format($rs->fields['heinv_lastdisc']) : $rs->fields['heinv_lastdisc'];

	$obj->heinv_price01 			= ' ' . is_numeric($rs->fields['heinv_price01']) ? number_format($rs->fields['heinv_price01']) : $rs->fields['heinv_price01'];
	$obj->heinv_pricedisc01 		= ' ' . is_numeric($rs->fields['heinv_pricedisc01']) ? number_format($rs->fields['heinv_pricedisc01']) : $rs->fields['heinv_pricedisc01'];
 	//$obj->heinv_price01 			= (float) $rs->fields['heinv_price01'];
 	$obj->heinv_pricedisc01 		= 1*$rs->fields['heinv_pricedisc01'];
 	$obj->heinv_isSP 				= 1*$rs->fields['heinv_isSP']; 
$obj->heinv_isadjgross 			= ' ' . is_numeric($rs->fields['heinv_isadjgross']) ? number_format($rs->fields['heinv_isadjgross']) : $rs->fields['heinv_isadjgross'];

 	$data[] = $obj;
 	$rs->MoveNext();
 }


 

$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 

	
print(stripslashes(json_encode($objResult)));

?>