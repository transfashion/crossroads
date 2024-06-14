<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];
$doc        = $_GET['doc'];


$sqlH = "select 
employee_contractno,
employee_pihak1name,
employee_pihak2name,
employee_pihak1jabatan,
jabatan_id,
jabatan_name,
employee_pihak2address,
employee_kontrak,
employee_startdate,
employee_enddate,
employee_pihak2location,
employee_tipekontrak,
employee_gapok,
employee_tunj_jabatan,
employee_tunj_telkom,
employee_tunj_transport,
employee_tunj_harian,
employee_intensif_longshift,
region_id
from master_employee 
where employee_contractno = '$id'";

$data = array();
$rs = $conn->Execute($sqlH);

while (!$rs->EOF) {
 
 	unset($obj);
 
	$obj->employee_contractno 		= $rs->fields['employee_contractno'];
	$obj->employee_pihak1name 		= $rs->fields['employee_pihak1name'];
	$obj->employee_pihak2name 		= $rs->fields['employee_pihak2name'];
	$obj->employee_pihak1jabatan	= $rs->fields['employee_pihak1jabatan'];
	$obj->jabatan_name				= $rs->fields['jabatan_name'];
	$obj->employee_pihak2address 	= $rs->fields['employee_pihak2address'];

	/* SPLIT TANGGAL KONTRAK*/
	$employee_kontrak = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_kontrak']));
	$split_kontrak	= explode("/", $employee_kontrak);
	$employee_kontrakyear 	= $split_kontrak [2];
	$employee_kontrakday	= $split_kontrak [0];
	if ($split_kontrak [1] == "01")	{
		$employee_kontrakmonth = "Januari";
		}
	elseif ($split_kontrak [1] == "02")	{
		$employee_kontrakmonth = "Februari";
		}
	elseif ($split_kontrak [1] == "03")	{
		$employee_kontrakmonth = "Maret";
		}
	elseif ($split_kontrak [1] == "04")	{
		$employee_kontrakmonth = "April";
		}
	elseif ($split_kontrak [1] == "05")	{
		$employee_kontrakmonth = "Mei";
		}
	elseif ($split_kontrak [1] == "06")	{
		$employee_kontrakmonth = "Juni";
		}
	elseif ($split_kontrak [1] == "07")	{
		$employee_kontrakmonth = "Juli";
		}
	elseif ($split_kontrak [1] == "08")	{
		$employee_kontrakmonth = "Agustus";
		}
	elseif ($split_kontrak [1] == "09")	{
		$employee_kontrakmonth = "September";
		}
	elseif ($split_kontrak [1] == "10")	{
		$employee_kontrakmonth = "Oktober";
		}
	elseif ($split_kontrak [1] == "11")	{
		$employee_kontrakmonth = "November";
		}
	elseif ($split_kontrak [1] == "12")	{
		$employee_kontrakmonth = "Desember";
		}
	$obj->employee_kontrak 			= "$employee_kontrakday $employee_kontrakmonth $employee_kontrakyear";
	
	/* SPLIT TANGGAL STARDATE*/	
	$employee_startdate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_startdate']));
	$split_stardate	= explode("/", $employee_startdate);
	$employee_startdateyear 	= $split_stardate [2];
	$employee_startdateday	= $split_stardate [0];

	if ($split_stardate [1] == "01")	{
		$split_stardatemonth = "Januari";
		}
	elseif ($split_stardate [1] == "02")	{
		$employee_startdatemonth = "Februari";
		}
	elseif ($split_stardate [1] == "03")	{
		$employee_startdatemonth = "Maret";
		}
	elseif ($split_stardate [1] == "04")	{
		$employee_startdatemonth = "April";
		}
	elseif ($split_stardate [1] == "05")	{
		$employee_startdatemonth = "Mei";
		}
	elseif ($split_stardate [1] == "06")	{
		$employee_startdatemonth = "Juni";
		}
	elseif ($split_stardate [1] == "07")	{
		$employee_startdatemonth = "Juli";
		}
	elseif ($split_stardate [1] == "08")	{
		$employee_startdatemonth = "Agustus";
		}
	elseif ($split_stardate [1] == "09")	{
		$employee_startdatemonth = "September";
		}
	elseif ($split_stardate [1] == "10")	{
		$employee_startdatemonth = "Oktober";
		}
	elseif ($split_stardate [1] == "11")	{
		$employee_startdatemonth = "November";
		}
	elseif ($split_stardate [1] == "12")	{
		$employee_startdatemonth = "Desember";
		}
	$obj->employee_startdate 		= "$employee_startdateday $employee_startdatemonth $employee_startdateyear";
	
	/* SPLIT TANGGAL ENDDATE*/		
	$employee_enddate = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['employee_enddate']));
	$split_enddate	= explode("/", $employee_enddate);
	$employee_enddateyear 	= $split_enddate [2];
	$employee_enddateday	= $split_enddate [0];

	if ($split_enddate [1] == "01")	{
		$employee_enddatemonth = "Januari";
		}
	elseif ($split_enddate [1] == "02")	{
		$employee_enddatemonth = "Februari";
		}
	elseif ($split_enddate [1] == "03")	{
		$employee_enddatemonth = "Maret";
		}
	elseif ($split_enddate [1] == "04")	{
		$employee_enddatemonth = "April";
		}
	elseif ($split_enddate [1] == "05")	{
		$employee_enddatemonth = "Mei";
		}
	elseif ($split_enddate [1] == "06")	{
		$employee_enddatemonth = "Juni";
		}
	elseif ($split_enddate [1] == "07")	{
		$employee_enddatemonth = "Juli";
		}
	elseif ($split_enddate [1] == "08")	{
		$employee_enddatemonth = "Agustus";
		}
	elseif ($split_enddate [1] == "09")	{
		$employee_enddatemonth = "September";
		}
	elseif ($split_enddate [1] == "10")	{
		$employee_enddatemonth = "Oktober";
		}
	elseif ($split_enddate [1] == "11")	{
		$employee_enddatemonth = "November";
		}
	elseif ($split_enddate [1] == "12")	{
		$employee_enddatemonth = "Desember";
		}
	$obj->employee_enddate 			= "$employee_enddateday $employee_enddatemonth $employee_enddateyear";
	
	$obj->employee_pihak2location 	= $rs->fields['employee_pihak2location'];
	$obj->employee_tipekontrak 		= $rs->fields['employee_tipekontrak'];
	$obj->employee_gapok 			= trim($rs->fields['employee_gapok']);
	$obj->employee_tunj_jabatan 	= trim($rs->fields['employee_tunj_jabatan']);
	$obj->employee_tunj_telkom 		= trim($rs->fields['employee_tunj_telkom']);
	$obj->employee_tunj_transport 	= trim($rs->fields['employee_tunj_transport']);
	$obj->employee_tunj_harian 		= trim($rs->fields['employee_tunj_harian']);
	$obj->employee_intensif_longshift 		= trim($rs->fields['employee_intensif_longshift']);
	
	$region_id = trim($rs->fields['region_id']);
	$SQLC					= 	"SELECT region_name FROM master_region WHERE region_id = '$region_id'";
	$rsC 					= 	$conn->execute($SQLC);
	$region_name 			= 	trim($rsC->fields['region_name']);
	$obj->region_id			=	$region_id;
	$obj->region_name		=	$region_name;
	
	//$obj->$region_id = trim($rs->fields['region_id']);
	//$obj->$region_name = trim($rs->fields['region_name']);
			
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