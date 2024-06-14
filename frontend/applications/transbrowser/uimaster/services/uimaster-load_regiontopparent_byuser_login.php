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


}




$SQL_REGION = "SELECT * FROM master_region WHERE region_parent='0' AND region_isdisabled='0'";

if ($SQL_CRITERIA) {
	$sql = sprintf($SQL_REGION, " AND ".$SQL_CRITERIA);
} else {
	$sql = sprintf($SQL_REGION, '');
}

$sql .= " ORDER BY region_name";
//print $sql;

//$sql += " ORDER BY region_name";


$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->selected = 0;
	$obj->region_id = $rs->fields['region_id'];
	$obj->region_name = $rs->fields['region_name'];
	$obj->region_nameshort = $rs->fields['region_nameshort'];
	if (!$DB_CRITERIA['selectall']->value) {
		/* cek apakah user bisa akses branch ini */
		$sql = "select * from  master_userregion where username='$username' and region_id='".$obj->region_id."'";
		$rsUser = $conn->Execute($sql);
		if ($rsUser->recordCount()) {
			$data[] = $obj;
		} 
	} else {
		$data[] = $obj;
	}

	$rs->MoveNext();
}



$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>