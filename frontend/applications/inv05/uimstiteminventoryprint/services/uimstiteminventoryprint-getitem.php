<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];

$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria */
	
	/* User Criteria */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_iteminventory_id', 'iteminventory_id', "%s='%s'");
	
}





if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_iteminventory WHERE $SQL_CRITERIA ";
} else {
	$sql = "SELECT * FROM master_iteminventory ";
}

//print $sql;

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->iteminventory_id 					= $rs->fields['iteminventory_id'];
	$obj->iteminventory_article				= $rs->fields['iteminventory_article'];
	$obj->iteminventory_color				= $rs->fields['iteminventory_color'];
	$obj->iteminventory_size				= $rs->fields['iteminventory_size'];
	$obj->iteminventory_material			= $rs->fields['iteminventory_material'];
	$obj->iteminventory_descr				= substr(str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_descr']),0,21);
	$obj->iteminventory_sellpricedefault 	= 1*$rs->fields['iteminventory_sellpricedefault'];
	$obj->iteminventory_discountdefault		= 1*$rs->fields['iteminventory_discountdefault'];
	
	$sql = sprintf("select iteminventorycolor_name from master_iteminventorycolor where iteminventorycolor_id = '%s'", $obj->iteminventory_color);
	$rsB = $conn->Execute($sql);
	$obj->iteminventorycolor_name		= $rsB->fields['iteminventorycolor_name'];
	
	$sql = sprintf("select iteminventorysize_name from master_iteminventorysize where iteminventorysize_id = '%s'", $obj->iteminventory_size);
	$rsB = $conn->Execute($sql);
	$obj->iteminventorysize_name		= $rsB->fields['iteminventorysize_name'];
	
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