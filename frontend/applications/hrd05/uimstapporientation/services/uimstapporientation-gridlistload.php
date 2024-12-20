<?
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   fakhri.rezarn    created date 14/09/2012 13:52
Program Persetujuan Orientasi
Filename: uimstapporientation-gridlistload.php
*/



if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];

$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}


	/* Default Criteria */
	/*
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_channel_id', 'channel_id', " %s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
	*/

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_employee_contractno', 'employee_contractno', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_employee_pihak1name', 'employee_pihak1name', "{db_field} LIKE '%{criteria_value}%'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_employee_pihak2name', 'employee_pihak2name', "{db_field} LIKE '%{criteria_value}%'");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_jabatan_id', 'jabatan_name', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_employee_tipekontrak', 'employee_tipekontrak', "{db_field} LIKE '%{criteria_value}%'");		
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_employee_pihak2location', 'employee_lokasikerja', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_employee_kontrak', 'employee_kontrak', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_employee_startdate', 'employee_startdate', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_ckh_employee_enddate', 'employee_enddate', "refParser");	
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_ckh_region_id', 'region_id', "refParser");	
}
	

if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_employee WHERE $SQL_CRITERIA ORDER BY employee_contractno DESC";
} else {
	$sql = "SELECT * FROM master_employee ORDER BY employee_contractno DESC";
}



$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$rs = $conn->SelectLimit($sql, $limit, $start);
$data = array();
while (!$rs->EOF) {

	unset($obj);
	$obj->employee_contractno = trim($rs->fields['employee_contractno']);
	$obj->employee_nik = trim($rs->fields['employee_nik']);
	$obj->employee_pihak1name = trim($rs->fields['employee_pihak1name']);
	$obj->employee_pihak2name = trim($rs->fields['employee_pihak2name']);
	$obj->employee_pihak1jabatan = trim($rs->fields['employee_pihak1jabatan']);
	$obj->jabatan_id = trim($rs->fields['jabatan_id']);
	$obj->jabatan_name = trim($rs->fields['jabatan_name']);
	$obj->employee_pihak2address = trim($rs->fields['employee_pihak2address']);
	$obj->employee_kontrak = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_kontrak']));
	$obj->employee_startdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_startdate']));
	$obj->employee_enddate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_enddate']));
	$obj->employee_pihak2location = trim($rs->fields['employee_pihak2location']);
	$obj->employee_tipekontrak = trim($rs->fields['employee_tipekontrak']);
	$obj->employee_gapok = trim($rs->fields['employee_gapok']);
	$obj->employee_tunj_jabatan = trim($rs->fields['employee_tunj_jabatan']);
	$obj->employee_tunj_telkom = trim($rs->fields['employee_tunj_telkom']);
	$obj->employee_tunj_transport = trim($rs->fields['employee_tunj_transport']);
	$obj->employee_tunj_harian = trim($rs->fields['employee_tunj_harian']);
	$obj->employee_intensif_longshift = trim($rs->fields['employee_intensif_longshift']);
	$obj->employee_createby = trim($rs->fields['employee_createby']);
	$obj->employee_createdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_createdate']));
	$obj->employee_modifyby = trim($rs->fields['employee_modifyby']);
	$obj->employee_modifydate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_modifydate']));
	$obj->employee_isdisabled = trim($rs->fields['employee_isdisabled']);
	
	$region_id = trim($rs->fields['region_id']);
	$SQLC					= 	"SELECT region_name FROM master_region WHERE region_id = '$region_id'";
	$rsC 					= 	$conn->execute($SQLC);
	$region_name 			= 	trim($rsC->fields['region_name']);
	$obj->region_id			=	$region_id;
	$obj->region_name		=	$region_name;
	
/*	$obj->region_id = trim($rs->fields['region_id']);
	$obj->region_name = trim($rs->fields['region_name']);*/
	$obj->rowid = trim($rs->fields['rowid']);

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