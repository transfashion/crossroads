<?
if (!defined('__SERVICE__')) {
	die("access denied");
}



$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];



$sql = "SELECT * FROM master_rekanan WHERE rekanan_id IN ('1020797', '1020729','1020826','1020827','1020158','1020800') ";


$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->rekanan_id = $rs->fields['rekanan_id'];
	$obj->rekanan_name = str_replace('"', "", $rs->fields['rekanan_name']);
	$data[] = $obj;

	$rs->MoveNext();
}


unset($obj);
$obj->rekanan_id = "0";
$obj->rekanan_name = "";
$data[] = $obj;



$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>