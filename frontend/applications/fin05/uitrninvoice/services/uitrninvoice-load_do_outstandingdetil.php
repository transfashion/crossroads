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

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_do_id', 'inventorymoving_id', " %s = '%s' ");

}

 
if ($SQL_CRITERIA) {
	$sql = "SELECT inventorymoving_id,
	inventorymovingdetil_line, 
	iteminventory_id, 
	inventorymovingdetil_qtypropose 
	FROM transaksi_inventorymovingdetil 
	WHERE 
	$SQL_CRITERIA 
	ORDER BY inventorymovingdetil_line";
} else {
	$sql = "
	SELECT 
	inventorymoving_id,
	inventorymovingdetil_line, 
	iteminventory_id,
	inventorymovingdetil_qtypropose
	FROM transaksi_inventorymovingdetil 
	ORDER BY inventorymovingdetil_line";
}


 
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	
             
	$obj->inventorymoving_id =$rs->fields['inventorymoving_id'];
	$obj->inventorymovingdetil_line = $rs->fields['inventorymovingdetil_line'];
	$obj->iteminventory_id = $rs->fields['iteminventory_id'];
	
	$obj->inventorymovingdetil_qtypropose = 1*$rs->fields['inventorymovingdetil_qtypropose'];

	$sql1 = "SELECT iteminventory_name,iteminventory_sellpricedefault FROM master_iteminventory WHERE iteminventory_id ='".$obj->iteminventory_id."' ";
	$rs1 =$conn->Execute($sql1);

	$obj->iteminventory_name = $rs1->fields['iteminventory_name'];
	$obj->inventorymovingdetil_idr = 1*$rs1->fields['iteminventory_sellpricedefault'];

	$obj->inventorymovingdetil_foreign = 0;

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