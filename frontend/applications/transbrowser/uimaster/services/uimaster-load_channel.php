<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$sql = " select * from master_setting where setting_id='channel_id'";
$rs = $conn->execute($sql);
$data = array();

	unset($obj);
    $channel_id = $rs->fields['setting_value'];
    $sqlC = "SELECT * from master_channel WHERE channel_id = '$channel_id'";
    $rsC = $conn->execute($sqlC);
	
    $obj->channel_id = $rsC->fields['channel_id'];
    $obj->channel_name = $rsC->fields['channel_name'];
    $obj->channel_number = $rsC->fields['channel_number'];
    $obj->channel_namereport = $rsC->fields['channel_namereport'];
    $obj->channel_address = $rsC->fields['channel_address'];
    
	$data[] = $obj;
 




$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>