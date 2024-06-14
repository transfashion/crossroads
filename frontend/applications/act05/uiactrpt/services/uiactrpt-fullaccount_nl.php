<?php
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];


$param = "";
$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$CRITERIA_DB = array();
	while (list($name, $value) = each($objCriteria)) {
		$CRITERIA_DB[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	

        $summarydate   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'summarydate', '', "{criteria_value}");
		$acc_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'acc_id', '', "{criteria_value}");
		
}
 
$SQL = "
 EXEC
 act05_FullAccount_NL '$summarydate' , '$acc_id'
" ;
 
 
$rs = $conn->Execute($SQL);
 
 
 
 
 $data = array(); 
 $dt = explode("-",$summarydate);
 $year = substr ($dt[0],2,2);
 $bln = str_pad($dt[1],2,"0",STR_PAD_LEFT);

$periode_id = $year . $bln;  
 
 
 
 WHILE (!$rs->EOF)
 {
    unset($obj);
        $obj->channel_id = 'MGP';
        $obj->periode_id =$rs->fields['periode_id'];
        $obj->acc_id = $rs->fields['acc_id'];
        $obj->acc_name = $rs->fields['acc_name'];
        $obj->jurnal_id = $rs->fields['jurnal_id'];
        
        
        $rekanan_name = $rs->fields['rekanan_name'];
        $rekanan_name = str_replace("'", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace(";", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace(",", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace("/", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace('"', "",$rs->fields['rekanan_name']);
        
        
           $obj->rekanan_name = $rekanan_name;
           
           $jurnal_descr = str_replace("'", "",$rs->fields['jurnal_descr']);
           $jurnal_descr = str_replace(";", "",$jurnal_descr);
           $jurnal_descr = str_replace(",", "",$jurnal_descr);
           $jurnal_descr = str_replace("&", "",$jurnal_descr);
           $jurnal_descr = str_replace("/", "",$jurnal_descr);
           $jurnal_descr = str_replace('"', "",$jurnal_descr);
           
         $obj->jurnal_descr = $jurnal_descr; 
        $obj->jurnal_bookdate = $rs->fields['jurnal_bookdate'];
        $obj->jurnal_source = $rs->fields['jurnal_source'];
        $obj->region_name = $rs->fields['region_name'];
        $obj->branch_name = $rs->fields['branch_name'];
        
        $obj->strukturunit_name = $rs->fields['strukturunit_name'];
        
        $obj->jurnalsaldoawal_idr = (float) $rs->fields['jurnalsaldoawal_idr'];
        $obj->jurnalsaldodebet_idr = (float) $rs->fields['jurnalsaldodebet_idr'];
        $obj->jurnalsaldokredit_idr = (float) $rs->fields['jurnalsaldokredit_idr'];
        $obj->jurnalsaldomutasi_idr = (float) $rs->fields['jurnalsaldomutasi_idr'];
        $obj->jurnalsaldoakhir_idr = (float) $rs->fields['jurnalsaldoakhir_idr'];
       
    $data[] = $obj;
    $rs->MoveNext();
    
 }
 
 
 
 

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data =  $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));
 
?> 