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
	
	 SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_region_id', 'region_id', " %s = '%s' ");

	
	// cek apakah di artikel ada unsur %
	$ENTRY = SQLUTIL::BuildCriteria(&$TEMP, $DB_CRITERIA, 'txtItemEntry', '', "{criteria_value}");
	$PREFX = substr($ENTRY, 0, 4);
	
	switch ($PREFX) {
		case "DES:" :
			$VALUE = trim(substr($ENTRY, 4, strlen($ENTRY)-4));
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'txtItemEntry', 'heinv_name', " {db_field} LIKE '%$VALUE%' ");
			break;
		
		default:
			if (strpos(" ".$ENTRY,"%")) {
				SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'txtItemEntry', 'heinv_art', " {db_field} LIKE '{criteria_value}' ");
			} else {
								SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'txtItemEntry', 'heinv_art', " ({db_field} = '{criteria_value}' OR heinv_id='{criteria_value}')");
			}		
	
	}
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'txtItemEntry', 'heinv_isdisabled', " {db_field} = 0 ");
}
 
//$sql = "SELECT * FROM master_heinv ORDER BY heinv_art ";
$SQL_ITEMINVENTORY  = "SELECT *, heinv_sizetag=(SELECT heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id=A.heinvctg_id and region_id = A.region_id) ";
$SQL_ITEMINVENTORY .= "FROM master_heinv A %s ORDER BY heinv_art ";
if ($SQL_CRITERIA) {
	$sql = sprintf($SQL_ITEMINVENTORY, " WHERE $SQL_CRITERIA ");
} else {
	$sql = sprintf($SQL_ITEMINVENTORY, " ");
}
 
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = str_replace(array('�', '"', "'", "\\"), array('-', '','',''), $rs->fields['heinv_name']);

	$obj->heinvgro_id = $rs->fields['heinvgro_id'];
	$obj->heinvctg_id = $rs->fields['heinvctg_id'];
	$obj->season_id = $rs->fields['season_id'];
	$obj->heinv_sizetag = $rs->fields['heinv_sizetag'];
	$obj->heinv_firstprice = 1*$rs->fields['heinv_priceori'];
	$obj->heinv_price01 = 1*$rs->fields['heinv_price01'];
	$obj->heinv_pricedisc01 = 1*$rs->fields['heinv_pricedisc01'];
	$obj->season_id = $rs->fields['season_id'];

	
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