<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];


$sql = "SELECT B.*, A.inventorymoving_date, A.inventorymoving_descr, A.inventorymoving_source, A.inventorymovingtype_id, 
		A.branch_id_source, A.branch_id_target, A.rekanan_id
	FROM transaksi_inventorymoving A inner join transaksi_inventorymovingdetil B
	ON A.inventorymoving_id = B.inventorymoving_id
	WHERE A.inventorymoving_id='$id'";

$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->type							= "ITEM";
	$obj->inventorymoving_id 			= $rs->fields['inventorymoving_id'];
	$obj->inventorymovingdetil_line 	= $rs->fields['inventorymovingdetil_line'];
	$obj->inventorymovingdetil_descr 	= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['inventorymovingdetil_descr']);
	$obj->inventorymovingdetil_qtyinit	= 1*$rs->fields['inventorymovingdetil_qtyinit'];
	$obj->inventorymovingdetil_qty 		= 1*$rs->fields['inventorymovingdetil_qty'];
	$obj->iteminventory_id 				= $rs->fields['iteminventory_id'];

	$obj->inventorymovingtype_id		= $rs->fields['inventorymovingtype_id'];
	$obj->inventorymoving_source		= $rs->fields['inventorymoving_source'];
	$obj->branch_id_source 				= $rs->fields['branch_id_source'];
	$obj->branch_id_target 				= $rs->fields['branch_id_target'];
	$obj->inventorymoving_descr 		= $rs->fields['inventorymoving_descr'];
	$obj->inventorymoving_date			= $rs->fields['inventorymoving_date'];
	$obj->rekanan_id					= $rs->fields['rekanan_id'];
	$obj->qty_print						= 1*$rs->fields['inventorymovingdetil_qty'];

	$sql = sprintf("select branch_name from master_branch where branch_id = '%s'", $obj->branch_id_source);
	$rsB = $conn->Execute($sql);
	$obj->branch_id_source_name			= $rsB->fields['branch_name'];

	$sql = sprintf("select branch_name from master_branch where branch_id = '%s'", $obj->branch_id_target);
	$rsB = $conn->Execute($sql);
	$obj->branch_id_target_name			= $rsB->fields['branch_name'];

	$sql = sprintf("select * from master_iteminventory where iteminventory_id = '%s'", $obj->iteminventory_id);
	$rsB = $conn->Execute($sql);
	$obj->iteminventory_name			= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_name']);
	$obj->iteminventory_article			= $rsB->fields['iteminventory_article'];
	$obj->iteminventory_color			= $rsB->fields['iteminventory_color'];
	$obj->iteminventory_size			= $rsB->fields['iteminventory_size'];
	$obj->iteminventory_sellpricedefault= 1*$rsB->fields['iteminventory_sellpricedefault'];
	$obj->iteminventory_discountdefault	= 1*$rsB->fields['iteminventory_discountdefault'];
	
	$sql = sprintf("select iteminventorycolor_name from master_iteminventorycolor where iteminventorycolor_id = '%s'", $obj->iteminventory_color);
	$rsB = $conn->Execute($sql);
	$obj->iteminventorycolor_name		= $rsB->fields['iteminventorycolor_name'];
	
	$sql = sprintf("select iteminventorysize_name from master_iteminventorysize where iteminventorysize_id = '%s'", $obj->iteminventory_size);
	$rsB = $conn->Execute($sql);
	$obj->iteminventorysize_name		= $rsB->fields['iteminventorysize_name'];

	$sql = sprintf("select rekanan_name from master_rekanan where rekanan_id = '%s'", $obj->rekanan_id);
	$rsB = $conn->Execute($sql);
	$obj->rekanan_name					= $rsB->fields['rekanan_name'];

	$data[] = $obj;
	$rs->MoveNext();
}


//$today = getdate();
$sql = "SELECT A.* 
	FROM transaksi_inventorymovingdetilex A
	WHERE A.inventorymoving_id='$id'";

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->type							= "EX";
	$obj->inventorymoving_id 			= $rs->fields['inventorymoving_id'];
	$obj->inventorymovingdetil_line 	= $rs->fields['inventorymovingdetilex_line'];
	$obj->inventorymovingdetil_descr 	= $rs->fields['inventorymovingdetilex_descr'];
	$obj->inventorymovingdetil_qtyinit	= 1*0;
	$obj->inventorymovingdetil_qty 		= 1*$rs->fields['inventorymovingdetilex_qty'];
	$obj->iteminventory_id 				= "0";

	$obj->inventorymovingtype_id		= "RV";
	$obj->inventorymoving_source		= "RV";
	$obj->branch_id_source 				= "0";
	$obj->branch_id_target 				= "0";
	$obj->inventorymoving_descr 		= $rs->fields['inventorymovingdetilex_descr'];
	$obj->inventorymoving_date			= '2000-1-1';
	$obj->rekanan_id					= "0";
	$obj->qty_print						= 1*$rs->fields['inventorymovingdetilex_qty'];


	$sql = sprintf("select branch_name from master_branch where branch_id = '%s'", $obj->branch_id_source);
	$rsB = $conn->Execute($sql);
	$obj->branch_id_source_name			= $rsB->fields['branch_name'];

	$sql = sprintf("select branch_name from master_branch where branch_id = '%s'", $obj->branch_id_target);
	$rsB = $conn->Execute($sql);
	$obj->branch_id_target_name			= $rsB->fields['branch_name'];

	$obj->iteminventory_name			= $rs->fields['inventorymovingdetilex_descr'];
	$obj->iteminventory_article			= $rs->fields['inventorymovingdetilex_article'];
	$obj->iteminventory_color			= $rs->fields['inventorymovingdetilex_color'];
	$obj->iteminventory_size			= $rs->fields['inventorymovingdetilex_size'];
	$obj->iteminventory_sellpricedefault= 1*$rs->fields['inventorymovingdetilex_priceidr'];
	$obj->iteminventory_discountdefault	= 1*0;
	
	$sql = sprintf("select iteminventorycolor_name from master_iteminventorycolor where iteminventorycolor_id = '%s'", $obj->iteminventory_color);
	$rsB = $conn->Execute($sql);
	$obj->iteminventorycolor_name		= $rsB->fields['iteminventorycolor_name'];
	
	$sql = sprintf("select iteminventorysize_name from master_iteminventorysize where iteminventorysize_id = '%s'", $obj->iteminventory_size);
	$rsB = $conn->Execute($sql);
	$obj->iteminventorysize_name		= $rsB->fields['iteminventorysize_name'];

	$sql = sprintf("select rekanan_name from master_rekanan where rekanan_id = '%s'", $obj->rekanan_id);
	$rsB = $conn->Execute($sql);
	$obj->rekanan_name					= $rsB->fields['rekanan_name'];

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