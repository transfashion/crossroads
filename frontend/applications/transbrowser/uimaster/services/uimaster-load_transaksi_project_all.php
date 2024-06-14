<?
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
	
	/* parsing criteria */
	$objname = 'obj_chk_masterdata_id';
	$columnname = 'project_id';
    
    
    
	if ($criteria[$objname]->checked) {
		$value = $criteria[$objname]->value;
		$_added_criteria = " $columnname = '$value'";
		if ($SQL_CRITERIA) {
			$SQL_CRITERIA .= " AND ".$_added_criteria;
		} else {
			$SQL_CRITERIA  = $_added_criteria;
		}

	}
	
	$objname = 'obj_chk_masterdata_name';
	$columnname = 'project_name';
	if ($criteria[$objname]->checked) {
		$value = $criteria[$objname]->value;
		$_added_criteria = " project_name LIKE '%$value%'";
		if ($SQL_CRITERIA) {
			$SQL_CRITERIA .= " AND ".$_added_criteria;
		} else {
			$SQL_CRITERIA  = $_added_criteria;
		}

	}	
	
	$objname = 'obj_region_id';
    $columnname = 'region_id';
	if ($criteria[$objname]->checked) {
		$value = $criteria[$objname]->value;
		$_added_criteria = " $columnname = '$value'";
		if ($SQL_CRITERIA) {
			$SQL_CRITERIA .= " AND ".$_added_criteria;
		} else {
			$SQL_CRITERIA  = $_added_criteria;
		}

	}	
	

}

 

if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM transaksi_project WHERE $SQL_CRITERIA ORDER BY project_name DESC";
} else {
	$sql = "SELECT * FROM transaksi_project ORDER BY project_name DESC";
}




$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->project_id = $rs->fields['project_id'];
	$obj->project_name = str_replace('"', "", $rs->fields['project_name']);
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