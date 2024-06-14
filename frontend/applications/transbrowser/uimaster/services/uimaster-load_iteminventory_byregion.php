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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'iteminventory_name', "iteminventory_id='{criteria_value}' OR {db_field} LIKE '%{criteria_value}%'");

}


$SQL_ITEMINVENTORY = "SELECT * FROM master_iteminventory %s ORDER BY iteminventory_name ";
if ($SQL_CRITERIA) {
	$sql = sprintf($SQL_ITEMINVENTORY, " WHERE iteminventory_isdisabled = 0 and iteminventorysubtype_id='INVMCH' AND (".$SQL_CRITERIA.")");
} else {
	$sql = sprintf($SQL_ITEMINVENTORY, "WHERE iteminventory_isdisabled = 0 and iteminventorysubtype_id='INVMCH'");
}




$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->iteminventory_id = $rs->fields['iteminventory_id'];
	$obj->iteminventory_name = str_replace(array('�', '"', "'", "\\"), array('-', '','',''), $rs->fields['iteminventory_name']);
	$obj->iteminventory_article = $rs->fields['iteminventory_article'];
	$item_region_id = $rs->fields['region_id'];
	$col = $rs->fields['iteminventory_color'];
	$size = $rs->fields['iteminventory_size'];

	$sql = sprintf("select region_path from master_region where region_id='%s'", $item_region_id);
	$rsRegion = $conn->Execute($sql);
	$region_id = substr($rsRegion->fields['region_path'], 0, 5);
	unset($rsRegion);


	/* terjemahkan color */
	$sql = sprintf("select iteminventorycolor_name from master_iteminventorycolor where region_id='%s' and iteminventorycolor_id='%s'", $region_id, $col);
	$rsColor = $conn->Execute($sql);
	$obj->colorname = $rsColor->fields['iteminventorycolor_name'];
	unset($rsColor);


	/* terjemahkan size */
	$sql = sprintf("select iteminventorysize_name from master_iteminventorysize where region_id='%s' and iteminventorysize_id='%s'", $region_id, $size);
	$rsSize = $conn->Execute($sql);
	$obj->size = $rsSize->fields['iteminventorysize_name'];
	unset($rsSize);



	$obj->iteminventory_color = $rs->fields['iteminventory_color'];
	$obj->iteminventory_material = $rs->fields['iteminventory_material'];
	$obj->iteminventory_size = $rs->fields['iteminventory_size'];
	$obj->iteminventory_factorycode = $rs->fields['iteminventory_factorycode'];

	$obj->season_id = $rs->fields['season_id'];

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