<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];


$TEMP = "";
$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();

	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
}



$sql = "select * from master_heinvsizetag";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->region_id = $rs->fields['region_id'];
	$obj->heinv_sizetag = $rs->fields['SIZETAG'];
	
	$obj->C01 = $rs->fields['C01'];
	$obj->C02 = $rs->fields['C02'];
	$obj->C03 = $rs->fields['C03'];
	$obj->C04 = $rs->fields['C04'];
	$obj->C05 = $rs->fields['C05'];
	$obj->C06 = $rs->fields['C06'];
	$obj->C07 = $rs->fields['C07'];
	$obj->C08 = $rs->fields['C08'];
	$obj->C09 = $rs->fields['C09'];
	$obj->C10 = $rs->fields['C10'];
	$obj->C11 = $rs->fields['C11'];
	$obj->C12 = $rs->fields['C12'];
	$obj->C13 = $rs->fields['C13'];
	$obj->C14 = $rs->fields['C14'];
	$obj->C15 = $rs->fields['C15'];
	$obj->C16 = $rs->fields['C16'];
	$obj->C17 = $rs->fields['C17'];
	$obj->C18 = $rs->fields['C18'];
	$obj->C19 = $rs->fields['C19'];
	$obj->C20 = $rs->fields['C20'];
	$obj->C21 = $rs->fields['C21'];
	$obj->C22 = $rs->fields['C22'];
	$obj->C23 = $rs->fields['C23'];
	$obj->C24 = $rs->fields['C24'];
	$obj->C25 = $rs->fields['C25'];	
	


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