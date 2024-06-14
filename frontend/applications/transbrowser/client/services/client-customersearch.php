<?php
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

	$searchkey = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'searchkey', '', "{criteria_value}");

}


$sql = "
select top 50 
customer_id, customer_namefull, customer_email, customer_phone, customer_typename, customer_paymdisc, gender_id
 from master_customer
where
(customer_namefull like '%$searchkey%' or customer_phone like '%$searchkey')
and customer_isvalid = 1
and customertype_id = 'C'
";


//echo $sql;

$rs  = $conn->Execute($sql);

$data = array();
while (!$rs->EOF) 
{
	
	$phone = trim($rs->fields["customer_phone"]);
	$phonepadleft = str_pad($phone, 20, ' ', STR_PAD_LEFT);
	$phoneview = substr($phone, 0, 5) . "***" . substr($phonepadleft, strlen($phonepadleft)-4, 4);
	
	unset($obj);
	$obj->customer_id = trim($rs->fields["customer_id"]);
	$obj->customer_name = trim($rs->fields["customer_namefull"]);
	$obj->customer_telp = trim($rs->fields["customer_phone"]);
	$obj->customer_telpview = $phoneview;
	$obj->customer_email = trim($rs->fields["customer_email"]);
	$obj->customer_typename = trim($rs->fields["customer_typename"]);
	$obj->customer_paymdisc = trim($rs->fields["customer_paymdisc"]);
	$obj->gender_id = trim($rs->fields["gender_id"]);
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
