<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

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
    
 
                
	
        $periode_id   = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'periode_id', '', "{criteria_value}");
        $type   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'type', '', "{criteria_value}");
        
}

       
       $sql_Temp = "DELETE FROM temp_sales WHERE periode_id = '$periode_id' and type = '$type'";
       $conn->execute($sql_Temp);
       
       
       $sqlJ = "SELECT jurnal_id FROM transaksi_jurnal WHERE periode_id = '$periode_id' and left(jurnal_id,2) ='SA' and left(jurnal_descr,2) ='$type'";
       $rsJ = $conn->execute($sqlJ);
       
       
       WHILE (!$rsJ->EOF)
       {
        
            $jurnal_id = $rsJ->Fields['jurnal_id'];
            
            $sqlD = "DELETE FROM transaksi_jurnaldetil WHERE jurnal_id = '$jurnal_id'";
            $conn->execute($sqlD);
            $rsJ->MoveNext();
       }
       
       
       $conn->execute("DELETE FROM transaksi_jurnal WHERE periode_id = '$periode_id' and left(jurnal_id,2) ='SA' and left(jurnal_descr,2) ='$type'");
         
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>