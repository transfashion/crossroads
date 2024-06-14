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
		$CRITERIA_DB[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'branch_id', '', "{criteria_value}");
		
	}


}


 

$SQL_REGION = "select distinct region_id,
region_name = (select region_name FROM master_region WHERE region_id = master_regionbranch.region_id)
from master_regionbranch where regionbranch_codesal in(select regionbranch_codesal from master_regionbranch where region_id='$region_id' 
and branch_id='$branch_id')";
 

$rs = $conn->Execute($SQL_REGION);
 

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