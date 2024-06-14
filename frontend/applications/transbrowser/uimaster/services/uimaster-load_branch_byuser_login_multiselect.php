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

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'region_id', 'B.region_id', " %s = '%s' ");

}


$SQL_BRANCH = ' select A.*, B.region_id 
			   	from master_branch A INNER JOIN master_regionbranch B
				on A.branch_id = B.branch_id
				%s
				ORDER BY branch_name
				';

//print $SQL_CRITERIA;

if ($SQL_CRITERIA) {
	$sql = sprintf($SQL_BRANCH, " WHERE ".$SQL_CRITERIA);
} else {
	$sql = sprintf($SQL_BRANCH, '');
}

 
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->selected = 0;
	$obj->branch_id = $rs->fields['branch_id'];
	$obj->branch_name = $rs->fields['branch_name'];
	$obj->branch_type = $rs->fields['branch_type'];


	if (!$DB_CRITERIA['selectall']->value) {
		/* cek apakah user bisa akses branch ini */
		$sql = "select * from  master_userbranch where username='$username' and branch_id='".$obj->branch_id."'";
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