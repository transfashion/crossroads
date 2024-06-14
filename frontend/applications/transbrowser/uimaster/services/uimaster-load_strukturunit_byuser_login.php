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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_strukturunit_id', 'strukturunit_id', " %s = '%s' ");

}




$SQL_STRUKTURUNIT = "SELECT * FROM master_strukturunit ";

if ($SQL_CRITERIA) {
	$sql = sprintf($SQL_STRUKTURUNIT, " WHERE ".$SQL_CRITERIA);
} else {
	$sql = sprintf($SQL_STRUKTURUNIT, '');
}



$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->selected = 0;
	$obj->strukturunit_id = $rs->fields['strukturunit_id'];
	$obj->strukturunit_name = $rs->fields['strukturunit_name'];

	if (!$DB_CRITERIA['selectall']->value) {
		/* cek apakah user bisa akses branch ini */
		$sql = "select * from  master_userstrukturunit where username='$username' and strukturunit_id='".$obj->strukturunit_id."'";
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