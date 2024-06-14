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

	$region_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'region_id', '', "{criteria_value}");
	$branch_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'branch_id', '', "{criteria_value}");
	$machine_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'machine_id', '', "{criteria_value}");
	$vou_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'vou_id', '', "{criteria_value}");

}

try {

	$data = new stdClass;
	$data->vou_id = $vou_id;
	$data->region_id = $region_id;
	$data->branch_id = $branch_id;
	$data->machine_id = $machine_id;
	$postdata = json_encode($data);

	// Prepare new cURL resource
	$ch = curl_init("http://172.18.10.38:8086/mark.php?");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	
	// Set HTTP Header for POST request
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($postdata))
	);
	
	// Submit the POST request
	$respond = curl_exec($ch);
	
	// Close cURL session handle
	curl_close($ch);	

	$result = json_decode($respond);
	if ($result->code != 0) {
		throw new Exception($result->message);
	}

	$payload = $result->payload;

	$objResult = new stdClass;
	$objResult->code = 0;
	$objResult->message = "";
	$objResult->success = $payload->success;
} catch (Exception $ex) {
	$objResult = new stdClass;
	$objResult->code = 99;
	$objResult->message = $ex->GetMessage();
	$objResult->voucher = null;
}
	
print(stripslashes(json_encode($objResult)));

?>