<?
if (!defined('__SERVICE__')) {
	die("access denied");
}



$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];

$sql = "SELECT heinvlogisticgroup_id, heinvlogisticgroup_name FROM master_heinvlogisticgroup ORDER BY heinvlogisticgroup_name";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->heinvlogisticgroup_id = $rs->fields['heinvlogisticgroup_id'];
	$obj->heinvlogisticgroup_name = $rs->fields['heinvlogisticgroup_name'];
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
