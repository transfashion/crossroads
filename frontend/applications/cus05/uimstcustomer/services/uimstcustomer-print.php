<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];


$sql = "SELECT * FROM master_customer WHERE customer_id='$id'";

$i=0;
$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	$i++;
	
	unset($obj);
	$obj->col 					= $i;
	$obj->row 					= $i;
	$obj->customer_id 			= $rs->fields['customer_id'];
	$obj->customer_title 		= $rs->fields['customer_title'];
	$obj->customer_namefull 	= $rs->fields['customer_namefull'];
	$obj->customer_address 		= $rs->fields['customer_address'];
	$obj->customer_city 		= $rs->fields['customer_city'];
	$obj->customer_email 		= $rs->fields['customer_email'];
	$obj->gender_id 			= $rs->fields['gender_id'];
	
	$obj->region 				= $rs->fields['region'];

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