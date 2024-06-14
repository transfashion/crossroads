<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
	}
	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'syn_id', 'syn_id', "%s = '%s'");

}

$sql = "SELECT * FROM transaksi_synserver WHERE $SQL_CRITERIA";
$rs = $conn->Execute($sql);
$lastdate = $rs->fields['syn_dateclientlast'];

//$sql = "SELECT * FROM master_iteminventory WHERE iteminventory_createdate >= dateadd(day,-1,'$lastdate') or  iteminventory_modifydate >= dateadd(day,-1,'$lastdate')";
$sql = "SELECT * FROM master_iteminventory WHERE iteminventory_createdate >= '2010-11-15 00:00:00.000' or  iteminventory_modifydate >= '2010-11-15 00:00:00.000'";
//$sql = "SELECT * FROM master_iteminventory WHERE left(iteminventory_id,10) = 'TS05100700364'";

/* COMMENT
 * Update item dimatikan per tanggal 23 November 2010 
 * Nanti dinyalakan lagi per tanggal 29 Pagi,
 * syntax
 * SELECT * FROM master_iteminventory WHERE lastupdatebatch='CLEARANCE201011'
 */
//$sql = "SELECT * FROM master_iteminventory WHERE iteminventory_id='0'";


//$sql = " select * from master_iteminventory where lastupdatebatch = 'CLEARANCE2010' ";

//$sql = " select * from master_iteminventory where left(iteminventory_id,2) = 'SP' ";


$rs = $conn->Execute($sql);
$data = array();

while (!$rs->EOF) {

	unset($obj);
    $obj->iteminventory_id = $rs->fields['iteminventory_id'];
    $obj->iteminventory_name =  str_replace(array(' ', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_name']);
    $obj->iteminventory_factorycode = $rs->fields['iteminventory_factorycode'];
    $obj->iteminventory_article = $rs->fields['iteminventory_article'];
    $obj->iteminventory_material = $rs->fields['iteminventory_material'];
    $obj->iteminventory_color = $rs->fields['iteminventory_color'];
    $obj->iteminventory_size = $rs->fields['iteminventory_size'];
    $obj->iteminventory_descr = str_replace(array(' ', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_descr']);
    $obj->iteminventory_isassembly = $rs->fields['iteminventory_isassembly'];
    $obj->iteminventory_isdisabled = $rs->fields['iteminventory_isdisabled'];
    $obj->iteminventory_isbufferenable = $rs->fields['iteminventory_isbufferenable'];
    $obj->iteminventory_createby = $rs->fields['iteminventory_createby'];
    $obj->iteminventory_createdate = $rs->fields['iteminventory_createdate'];
    $obj->iteminventory_modifyby = $rs->fields['iteminventory_modifyby']==null ? 'NULL' : $rs->fields['iteminventory_modifyby'];
    $obj->iteminventory_modifydate = $rs->fields['iteminventory_modifydate']==null ? 'NULL' : $rs->fields['iteminventory_modifydate'];
    $obj->iteminventory_buypricedefault = 1*$rs->fields['iteminventory_buypricedefault'];
    $obj->iteminventory_sellpricedefault = 1*$rs->fields['iteminventory_sellpricedefault'];
    $obj->iteminventory_discountdefault = 1*$rs->fields['iteminventory_discountdefault'];
    $obj->iteminventory_minsupplies = 1*$rs->fields['iteminventory_minsupplies'];
    $obj->iteminventory_maxsupplies = 1*$rs->fields['iteminventory_maxsupplies'];
    $obj->iteminventory_format = $rs->fields['iteminventory_format'];
    $obj->iteminventorytype_id = $rs->fields['iteminventorytype_id'];
    $obj->iteminventorysubtype_id = $rs->fields['iteminventorysubtype_id'];
    $obj->iteminventorygroup_id = $rs->fields['iteminventorygroup_id'];
    $obj->iteminventorysubgroup_id = $rs->fields['iteminventorysubgroup_id'];
    $obj->iteminventoryunittype_id = $rs->fields['iteminventoryunittype_id'];
    $obj->iteminventoryunit_id = $rs->fields['iteminventoryunit_id'];
    $obj->region_id = $rs->fields['region_id'];
    $obj->season_id = $rs->fields['season_id'];
    $obj->channel_id = $rs->fields['channel_id'];
    $obj->rowid = $rs->fields['rowid'];
	
	$data[] = $obj;
	$rs->MoveNext();
}


$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>