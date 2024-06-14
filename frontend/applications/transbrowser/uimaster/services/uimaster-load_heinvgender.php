<?
if (!defined('__SERVICE__')) {
	die("access denied");
}


/*
$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];

$sql = "SELECT * FROM master_region ORDER BY region_name";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->region_id = $rs->fields['region_id'];
	$obj->region_name = $rs->fields['region_name'];
	$obj->region_nameshort = $rs->fields['region_nameshort'];
	$data[] = $obj;

	$rs->MoveNext();
}
*/

$data = array(
	(object) array('heinvctg_gender'=>'A', 'heinvctg_gendername'=>'ALL'), 
	(object) array('heinvctg_gender'=>'F', 'heinvctg_gendername'=>'Female'), 
	(object) array('heinvctg_gender'=>'M', 'heinvctg_gendername'=>'Male'), 
);


$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>
