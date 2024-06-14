<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	set_time_limit(100);





	$sql = "select * from master_iteminventory where iteminventory_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->iteminventory_id = trim($rs->fields['iteminventory_id']);
	$objh->iteminventory_name = trim(str_replace(array("'", '"'), array("", ""), $rs->fields['iteminventory_name']));
	$objh->iteminventory_factorycode = trim($rs->fields['iteminventory_factorycode']);
	$objh->iteminventory_article = trim($rs->fields['iteminventory_article']);
	$objh->iteminventory_material = trim($rs->fields['iteminventory_material']);
	$objh->iteminventory_color = trim($rs->fields['iteminventory_color']);
	$objh->iteminventory_size = trim($rs->fields['iteminventory_size']);
	$objh->iteminventory_descr = trim(str_replace(array("'", '"'), array("", ""), $rs->fields['iteminventory_descr']));
	$objh->iteminventory_isconsumable = trim($rs->fields['iteminventory_isconsumable']);
	$objh->iteminventory_isassembly = trim($rs->fields['iteminventory_isassembly']);
	$objh->iteminventory_isconsinyasi = trim($rs->fields['iteminventory_isconsinyasi']);
	$objh->iteminventory_isempty = trim($rs->fields['iteminventory_isempty']);
	$objh->iteminventory_isdisabled = trim($rs->fields['iteminventory_isdisabled']);
	$objh->iteminventory_isbufferenable = trim($rs->fields['iteminventory_isbufferenable']);
	$objh->iteminventory_createby = trim($rs->fields['iteminventory_createby']);
	$objh->iteminventory_createdate = trim($rs->fields['iteminventory_createdate']);
	$objh->iteminventory_modifyby = trim($rs->fields['iteminventory_modifyby']);
	$objh->iteminventory_modifydate = trim($rs->fields['iteminventory_modifydate']);
	$objh->iteminventory_buypricedefault = trim($rs->fields['iteminventory_buypricedefault']);
	$objh->iteminventory_sellpricedefault = trim($rs->fields['iteminventory_sellpricedefault']);
	$objh->iteminventory_discountdefault = trim($rs->fields['iteminventory_discountdefault']);
	$objh->iteminventory_minsupplies = trim($rs->fields['iteminventory_minsupplies']);	
	$objh->iteminventory_maxsupplies = trim($rs->fields['iteminventory_maxsupplies']);	
	$objh->iteminventory_format = trim($rs->fields['iteminventory_format']);	
	$objh->iteminventorytype_id = trim($rs->fields['iteminventorytype_id']);
	$objh->iteminventorysubtype_id = trim($rs->fields['iteminventorysubtype_id']);	
	$objh->iteminventorygroup_id = trim($rs->fields['iteminventorygroup_id']);	
	$objh->iteminventorysubgroup_id = trim($rs->fields['iteminventorysubgroup_id']);	
	$objh->iteminventoryunittype_id = trim($rs->fields['iteminventoryunittype_id']);	
	$objh->iteminventoryunit_id = trim($rs->fields['iteminventoryunit_id']);	
	$objh->region_id = trim($rs->fields['region_id']);	
	$objh->season_id = trim($rs->fields['season_id']);	
	$objh->channel_id = trim($rs->fields['channel_id']);	
	$objh->rowid = trim($rs->fields['rowid']);
	
	 
	 
	
	
	
 
	/* select Region name 
	$sql = sprintf("select region_id from master_region where region_id='%s'", $objh->region_id);
	$rs = $conn->Execute($sql);
	$objh->region_name = trim($rs->fields['region_name']);
	
	*/
	
	
	$data[0]['H'] = $objh;
	


$sql = "select * from master_iteminventoryprop where iteminventory_id='$id'";
$rs  = $conn->Execute($sql);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);
 
        
	$obj->iteminventory_id = trim($rs->fields['iteminventory_id']);
	$obj->iteminventoryprop_line = $rs->fields['iteminventoryprop_line'];
	$obj->iteminventoryprop_name = $rs->fields['iteminventoryprop_name'];
	$obj->iteminventoryprop_value = $rs->fields['iteminventoryprop_value'];
	$obj->iteminventoryprop_descr = $rs->fields['iteminventoryprop_descr'];
	$obj->rowid = $rs->fields['rowid'];	

	$arrdata[] = $obj;
	$rs->MoveNext();
}
$data[0]['D']['Prop'] = $arrdata;


$sql = "select * from master_iteminventorylog where iteminventory_id='$id'";
$rs  = $conn->Execute($sql);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->log_id = trim($rs->fields['iteminventory_id']);
	$obj->log_line = $rs->fields['iteminventorylog_line'];
	$obj->log_date = SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['iteminventorylog_date']));
	$obj->log_action = $rs->fields['iteminventorylog_action'];
	$obj->log_table = $rs->fields['iteminventorylog_table'];
	$obj->log_descr = $rs->fields['iteminventorylog_descr'];
	$obj->log_descr = $rs->fields['iteminventorylog_lastvalue'];
	$obj->log_username = $rs->fields['iteminventorylog_username'];
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