<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$start 		= $_POST['start'];

$sql = "SELECT * FROM master_iteminventorysubgroup ";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->iteminventorysubgroup_id = $rs->fields['iteminventorysubgroup_id'];
	$obj->iteminventorysubgroup_name = $rs->fields['iteminventorysubgroup_name'];

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