<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];


$pricinttypes = array();
$pricinttypes[] = array('REG', 'Reguler');
$pricinttypes[] = array('PRO', 'Promo');
$pricinttypes[] = array('MDW', 'MarkDown');
$pricinttypes[] = array('LOC', 'LocalPricing');



foreach ($pricinttypes as $pct) {
	unset($obj);
	$obj->pricingtype_id = $pct[0];
	$obj->pricingtype_name = $pct[1];
	$data[] = $obj;
}



$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>