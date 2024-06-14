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
	$data->test = 'xxx';
	$postdata = json_encode($data);

	// Prepare new cURL resource
	$ch = curl_init("http://172.18.10.38:8086/cek.php?id=$vou_id");
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
	
	// {"id":"2301900291916","batch_id":"23019","value":"250000.00","isactive":1,"activedate":"2023-06-23","expiredate":"2023-08-27","isused":0,"useddate":null,"userid":null,"descr":"GEX E- voucher Geox"}}

	$ed = explode('-', $payload->expiredate);

	$voucher = new stdClass;
	$voucher->Id = $payload->id;
	$voucher->BatchId = $payload->batch_id;
	$voucher->Value = (float)$payload->value;
	$voucher->isActive = (bool)$payload->isactive;
	$voucher->ActiveDate = $payload->activedate;
	$voucher->ExpireDate = $payload->expiredate; 
	$voucher->ED_year = $ed[0];
	$voucher->ED_month = $ed[1];
	$voucher->ED_date = $ed[2];
	$voucher->isUsed = (bool)$payload->isused;
	$voucher->UsedDate = $payload->useddate;
	$voucher->UserId = $payoad->userid;
	$voucher->UsedDate = $payload->useddate;
	$voucher->Descr = $payload->descr;

	$objResult = new stdClass;
	$objResult->code = 0;
	$objResult->message = "";
	$objResult->voucher = $voucher;
} catch (Exception $ex) {
	$objResult = new stdClass;
	$objResult->code = 99;
	$objResult->message = $ex->GetMessage();
	$objResult->voucher = null;
}
	
print(stripslashes(json_encode($objResult)));

?>