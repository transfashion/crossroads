<?
if (!defined('__SERVICE__')) {
	die("access denied");
}




$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
	}

	/* parsing criteria */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'heinvctg_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'heinvctg_name', "( {db_field} LIKE '%{criteria_value}%'  OR heinvctg_id = '{criteria_value}' )");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_cbo_region_id', 'region_id', " %s = '%s' ");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_txt_heinvgro_id', 'heinvgro_id', " %s = '%s' ");	

}



if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_heinvctg WHERE $SQL_CRITERIA AND heinvctg_isdisabled = 0 ORDER BY heinvctg_name DESC";
} else {
	$sql = "SELECT * FROM master_heinvctg WHERE heinvctg_isdisabled=0 ORDER BY heinvctg_name DESC";
}




$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->heinvctg_id = $rs->fields['heinvctg_id'];
	$obj->heinvctg_name = str_replace('"', "", $rs->fields['heinvctg_name']);
	$obj->heinvctg_sizetag = $rs->fields['heinvctg_sizetag'];
		
	
	$region_id = $rs->fields['region_id'];
	$sql = "SELECT * FROM master_heinvsizetag WHERE region_id='$region_id' AND SIZETAG='".$obj->heinvctg_sizetag."' ";
	$rsI = $conn->Execute($sql);
	$obj->DESCR= $rsI->fields['DESCR'];
	$obj->C01  = $rsI->fields['C01'];
	$obj->C02  = $rsI->fields['C02'];
	$obj->C03  = $rsI->fields['C03'];
	$obj->C04  = $rsI->fields['C04'];
	$obj->C05  = $rsI->fields['C05'];
	$obj->C06  = $rsI->fields['C06'];
	$obj->C07  = $rsI->fields['C07'];
	$obj->C08  = $rsI->fields['C08'];
	$obj->C09  = $rsI->fields['C09'];
	$obj->C10  = $rsI->fields['C10'];
	$obj->C11  = $rsI->fields['C11'];
	$obj->C12  = $rsI->fields['C12'];
	$obj->C13  = $rsI->fields['C13'];
	$obj->C14  = $rsI->fields['C14'];
	$obj->C15  = $rsI->fields['C15'];
	$obj->C16  = $rsI->fields['C16'];
	$obj->C17  = $rsI->fields['C17'];
	$obj->C18  = $rsI->fields['C18'];
	$obj->C19  = $rsI->fields['C19'];
	$obj->C20  = $rsI->fields['C20'];
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