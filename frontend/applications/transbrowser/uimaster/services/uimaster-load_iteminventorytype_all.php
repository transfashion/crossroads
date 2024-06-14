<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$start 		= $_POST['start'];

$sql = "SELECT * FROM master_iteminventorytype ";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->iteminventorytype_id = $rs->fields['iteminventorytype_id'];
	$obj->iteminventorytype_name = $rs->fields['iteminventorytype_name'];

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