<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'iteminventory_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'ocdetil_descr', "{db_field} LIKE '%{criteria_value}%'");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_ref_id', 'oc_id', " %s = '%s' ");



}




$SQL_ITEMINVENTORY = "SELECT * FROM transaksi_ocdetil %s ";
if ($SQL_CRITERIA) {
	$sql = sprintf($SQL_ITEMINVENTORY, " WHERE ".$SQL_CRITERIA);
} else {
	$sql = sprintf($SQL_ITEMINVENTORY);
}




$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->oc_id = $rs->fields['oc_id'];
	$obj->ocdetil_line = $rs->fields['ocdetil_line'];
	$obj->iteminventory_id = $rs->fields['iteminventory_id'];
	$obj->iteminventory_name = str_replace(array('"', "'", "\\"), array('*','*','*'), $rs->fields['ocdetil_descr']);

	$obj->ocdetil_qty = 1*$rs->fields['ocdetil_qty'];
	
	$obj->ocdetil_art = $rs->fields['ocdetil_art'];
	$obj->ocdetil_col = $rs->fields['ocdetil_col'];
	$obj->ocdetil_mat = $rs->fields['ocdetil_mat'];
	$obj->ocdetil_size = $rs->fields['ocdetil_size'];
	$obj->ocdetil_factorycode = $rs->fields['ocdetil_factorycode'];
	$obj->iteminventorygroup_id = $rs->fields['iteminventorygroup_id'];
	$obj->iteminventorysubgroup_id = $rs->fields['iteminventorysubgroup_id'];
	$obj->iteminventoryunit_id = 'PCS';

	/* cari nilai yang telah diterima */
	$sql = "select inventorymovingdetil_qty=SUM(inventorymovingdetil_qty) 
		    from transaksi_inventorymoving A inner join transaksi_inventorymovingdetil B
            on A.inventorymoving_id = B.inventorymoving_id AND B.ref_id = '".$obj->oc_id."' and B.ref_line=".$obj->ocdetil_line." ";
    $rsI = $conn->Execute($sql);
	$obj->ocdetil_qtyreceived = 1*$rsI->fields['inventorymovingdetil_qty'];        
	$obj->ocdetil_qtyreceive = $obj->ocdetil_qty - $obj->ocdetil_qtyreceived; 

	//if 	($obj->ocdetil_qtyreceive>0) {
		$data[] = $obj;
	//}

	$rs->MoveNext();
}



$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));



?>