<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$start 		= $_POST['start'];

$sql = "SELECT * FROM master_iteminventorygroup ";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->iteminventorygroup_id = $rs->fields['iteminventorygroup_id'];
	$obj->iteminventorygroup_name = $rs->fields['iteminventorygroup_name'];

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