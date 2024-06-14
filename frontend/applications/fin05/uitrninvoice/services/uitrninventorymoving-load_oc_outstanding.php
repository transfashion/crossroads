<?
if (!defined('__SERVICE__')) {
	die("access denied");
}




$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];



$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria  */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'oc_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'oc_descr', " (oc_id='{criteria_value}' OR {db_field} LIKE '%{criteria_value}%') ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_rekanan_id', 'rekanan_id', " %s = '%s' ");
	
	
}


//print $SQL_CRITERIA;
//die();

$sql = "SELECT * FROM transaksi_oc WHERE oc_isposted=1 AND oc_isclosed=0 AND $SQL_CRITERIA ORDER BY oc_date DESC";



$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->oc_id = $rs->fields['oc_id'];
	$obj->oc_descr = str_replace('"', "", $rs->fields['oc_descr']);
	$obj->oc_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['oc_date']));
	
	/* ambil total barang yang diorder */
	$sql = "SELECT ocdetil_qty=SUM(ocdetil_qty) FROM transaksi_ocdetil WHERE oc_id='".$obj->oc_id."' AND ocdetil_isclosed=0";
	$rsI = $conn->Execute($sql);	
	$obj->oc_qtyordered = 1*$rsI->fields['ocdetil_qty'];
	
	
	/* cari nilai yang telah diterima */
	$sql = "select inventorymovingdetil_qty=SUM(inventorymovingdetil_qty) 
		    from transaksi_inventorymoving A inner join transaksi_inventorymovingdetil B
            on A.inventorymoving_id = B.inventorymoving_id AND B.ref_id = '".$obj->oc_id."'  ";
	$rsI = $conn->Execute($sql);
	$obj->oc_qtyreceived = 1*$rsI->fields['inventorymovingdetil_qty'];
	
	$obj->oc_qtyoutstanding = $obj->oc_qtyordered - $obj->oc_qtyreceived;
	//if ($obj->oc_qtyoutstanding) {
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