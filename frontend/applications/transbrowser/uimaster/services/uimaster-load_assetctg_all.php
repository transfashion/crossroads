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
	$columnname = 'assetctg_id';
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
	$columnname = 'assetctg_name';
	if ($criteria[$objname]->checked) {
		$value = $criteria[$objname]->value;
		$_added_criteria = " rekanan_name LIKE '%$value%'";
		if ($SQL_CRITERIA) {
			$SQL_CRITERIA .= " AND ".$_added_criteria;
		} else {
			$SQL_CRITERIA  = $_added_criteria;
		}

	}	
	
	$objname = 'obj_chk_masterdata_advance';
	if ($criteria[$objname]->checked) {
		$value = $criteria[$objname]->value;
		$_added_criteria = " $value ";
		if ($SQL_CRITERIA) {
			$SQL_CRITERIA .= " AND ".$_added_criteria;
		} else {
			$SQL_CRITERIA  = $_added_criteria;
		}

	}	

	$_added_criteria = " assetctg_isdisabled='0' ";
	if ($SQL_CRITERIA) {
		$SQL_CRITERIA .= " AND ".$_added_criteria;
	} else {
		$SQL_CRITERIA  = $_added_criteria;
	}
	
}



if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_assetctg WHERE $SQL_CRITERIA ORDER BY assetctg_name";
} else {
	$sql = "SELECT * FROM master_assetctg ORDER BY assetctg_name";
}


 


$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->assetctg_id = $rs->fields['assetctg_id'];
	$obj->assetctg_name = str_replace('"', "", $rs->fields['assetctg_name']);
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