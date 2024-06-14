<?php
if (!defined('__SERVICE__')) {
	die("access denied");
}

  /*

define('ADODB_DIR', '../../adodb');
require_once ADODB_DIR.'/adodb-exceptions.inc.php';
require_once ADODB_DIR.'/adodb.class.php';

//require_once '../../../../inc/sqlutil.inc.php';

try {

	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection($db_local[type]);
	$DSN_LOCAL  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db_local[host]."; DATABASE=".$db_local[name]."; UID=".$db_local[user]."; PWD=".$db_local[pass].";";
	$conn->Connect($DSN_LOCAL);
} catch (exception $e) {
	print $e->GetMessage();	
}

*/

 
$__ID 		= $_POST['__ID'];

$opnameproject_id = $__ID;
 

try {
		
		try {
			$conn->BeginTrans();

            
                /* Periksa Ke Master Heinv */
               
                $SQL  ="SELECT opnameproject_id='$opnameproject_id',
						  opnameprojectsum_line = 10*ROW_NUMBER() OVER (ORDER BY B.barcode),
					      barcode=B.barcode, 
						  qty=SUM(item_qty)
						
						FROM transaksi_opname A inner join transaksi_opnamedetil B
						     on A.opname_id = B.opname_id
						WHERE A.opnameproject_id='$opnameproject_id' AND A.opname_isposted=1
						GROUP BY B.barcode";
                  $rs = $conn->execute($SQL)  ; 
                            
                	while (!$rs->EOF) 
                        {
                            
                           $itemBarcode  = $rs->fields['barcode'];
                           $heinv_id  = $rs->fields['barcode'];
                           
                           
            	           $sqlI="SELECT heinv_id FROM master_heinvitem WHERE heinv_id = '$heinv_id' OR heinvitem_barcode = '$item_Barcode'";
                            $rsI = $conn->execute($sqlI);
                            
                            if (!$rsI->recordCount()) {
		                      	throw new Exception('ID ' . $item_barcode . ' Has Not been registered');
		                      }
                            $rs-> MoveNext();
                       }
                        
           	$sql_M = "SELECT * FROM transaksi_opnameprojectmoving WHERE opnameprojectmoving_id='$opnameproject_id' ";
			$rs_M  = $conn->Execute($sql_M);
            $_id =  $rs_M->fields['hemoving_id'];
            
    
            
            $sql_I = "  
            
            SET NOCOUNT ON
            SELECT opnameproject_id='$opnameproject_id',
						  opnameprojectsum_line = 10*ROW_NUMBER() OVER (ORDER BY B.barcode),
					      barcode=B.barcode, 
						  qty=SUM(item_qty)
						INTO #temp1
						FROM transaksi_opname A inner join transaksi_opnamedetil B
						     on A.opname_id = B.opname_id
						WHERE A.opnameproject_id='$opnameproject_id'
						GROUP BY B.barcode
 
						
						SELECT A.opnameproject_id,
						opnameprojectsum_line =   10*ROW_NUMBER() OVER (ORDER BY B.heinv_id),
						heinv_id = ISNULL(B.heinv_id,A.barcode),
						heinvitem_size = ISNULL(B.heinvitem_size,''),
						heinvitem_colnum = ISNULL('A' + B.heinvitem_colnum,''),
						qty = SUM(qty),
						qty_reserved = 0
						into #temp2
						FROM #temp1 A left join master_heinvitem B on 
						A.barcode = B.heinvitem_barcode 
						GROUP by A.opnameproject_id,B.heinv_id,
						B.heinvitem_size,
						B.heinvitem_colnum,
						A.barcode
		 
						 
						select * 
						INTO #temp3
						from #temp2
						pivot (sum(qty) for heinvitem_colnum in (A01,A02,A03,A04,A05,A06,A07,A08,A09,A10,A11,A12,A13,A14,A15,A16,A17,A18,A19,A20,A21,A22,A23,A24,A25)) as c
					 
						 
			 			select hemoving_id ='$_id',
						hemovingdetil_line = 10*ROW_NUMBER() OVER (ORDER BY B.heinv_id),
						A.heinv_id,
						B.heinv_art, B.heinv_mat,B.heinv_col, B.heinv_name,
						A01 = ISNULL(A.A01,0),
						A02 = ISNULL(A.A02,0),
						A03 = ISNULL(A.A03,0),
						A04 = ISNULL(A.A04,0),
						A05 = ISNULL(A.A05,0),
						A06 = ISNULL(A.A06,0),
						A07 = ISNULL(A.A07,0),
						A08 = ISNULL(A.A08,0),
						A09 = ISNULL(A.A09,0),
						A10 = ISNULL(A.A10,0),
						A11 = ISNULL(A.A11,0),
						A12 = ISNULL(A.A12,0),
						A13 = ISNULL(A.A13,0),
						A14 = ISNULL(A.A14,0),
						A15 = ISNULL(A.A15,0),
						A16 = ISNULL(A.A16,0),
						A17 = ISNULL(A.A17,0),
						A18 = ISNULL(A.A18,0),
						A29 = ISNULL(A.A19,0),
						A20 = ISNULL(A.A20,0),
						A21 = ISNULL(A.A21,0),
						A22 = ISNULL(A.A22,0),
						A23 = ISNULL(A.A23,0),
						A24 = ISNULL(A.A24,0),
						A25 = ISNULL(A.A25,0),
						B01= 0,
						B02= 0,
						B03= 0,
						B04= 0,
						B05= 0,
						B06= 0,
						B07= 0,
						B08= 0,
						B09= 0,
						B10= 0,
						B11= 0,
						B12= 0,
						B13= 0,
						B14= 0,
						B15= 0,
						B16= 0,
						B17= 0,
						B18= 0,
						B19= 0,
						B20= 0,
						B21= 0,
						B22= 0,
						B23= 0,
						B24= 0,
						B25= 0,
						C01= 0,
						C02= 0,
						C03= 0,
						C04= 0,
						C05= 0,
						C06= 0,
						C07= 0,
						C08= 0,
						C09= 0,
						C10= 0,
						C11= 0,
						C12= 0,
						C13= 0,
						C14= 0,
						C15= 0,
						C16= 0,
						C17= 0,
						C18= 0,
						C19= 0,
						C20= 0,
						C21= 0,
						C22= 0,
						C23= 0,
						C24= 0,
						C25= 0 
						INTO #temp4
						  from #temp3 A inner join master_heinv B on A.heinv_id = B.heinv_id 
						
                        select * from #temp4
						DROP TABLE #temp1
						DROP TABLE #temp2
						DROP TABLE #temp3
                        DROP TABLE #temp4
						 " ;
                         
                         
            $rs_I = $conn->execute($sql_I);           
          
            
            	while (!$rs_I->EOF) 
                {
                    
                       
            	   unset($obj);
                   $obj->hemoving_id = $rs_I->fields['hemoving_id'];
                   $obj->hemovingdetil_line = (float) $rs_I->fields['hemovingdetil_line'];
                   $obj->heinv_id = $rs_I->fields['heinv_id'];
                   $obj->heinv_art = $rs_I->fields['heinv_art'];
                   $obj->heinv_mat = $rs_I->fields['heinv_mat'];
                   $obj->heinv_col = $rs_I->fields['heinv_col'];
                   $obj->heinv_name = $rs_I->fields['heinv_name'];
                   $obj->A01=$rs_I->fields['A01'];
                    $obj->A02=$rs_I->fields['A02'];
                    $obj->A03=$rs_I->fields['A03'];
                    $obj->A04=$rs_I->fields['A04'];
                    $obj->A05=$rs_I->fields['A05'];
                    $obj->A06=$rs_I->fields['A06'];
                    $obj->A07=$rs_I->fields['A07'];
                    $obj->A08=$rs_I->fields['A08'];
                    $obj->A09=$rs_I->fields['A09'];
                    $obj->A10=$rs_I->fields['A10'];
                    $obj->A11=$rs_I->fields['A11'];
                    $obj->A12=$rs_I->fields['A12'];
                    $obj->A13=$rs_I->fields['A13'];
                    $obj->A14=$rs_I->fields['A14'];
                    $obj->A15=$rs_I->fields['A15'];
                    $obj->A16=$rs_I->fields['A16'];
                    $obj->A17=$rs_I->fields['A17'];
                    $obj->A18=$rs_I->fields['A18'];
                    $obj->A19=$rs_I->fields['A19'];
                    $obj->A20=$rs_I->fields['A20'];
                    $obj->A21=$rs_I->fields['A21'];
                    $obj->A22=$rs_I->fields['A22'];
                    $obj->A23=$rs_I->fields['A23'];
                    $obj->A24=$rs_I->fields['A24'];
                    $obj->A25=$rs_I->fields['A25'];
                    $obj->B01=$rs_I->fields['B01'];
                    $obj->B02=$rs_I->fields['B02'];
                    $obj->B03=$rs_I->fields['B03'];
                    $obj->B04=$rs_I->fields['B04'];
                    $obj->B05=$rs_I->fields['B05'];
                    $obj->B06=$rs_I->fields['B06'];
                    $obj->B07=$rs_I->fields['B07'];
                    $obj->B08=$rs_I->fields['B08'];
                    $obj->B09=$rs_I->fields['B09'];
                    $obj->B10=$rs_I->fields['B10'];
                    $obj->B11=$rs_I->fields['B11'];
                    $obj->B12=$rs_I->fields['B12'];
                    $obj->B13=$rs_I->fields['B13'];
                    $obj->B14=$rs_I->fields['B14'];
                    $obj->B15=$rs_I->fields['B15'];
                    $obj->B16=$rs_I->fields['B16'];
                    $obj->B17=$rs_I->fields['B17'];
                    $obj->B18=$rs_I->fields['B18'];
                    $obj->B19=$rs_I->fields['B19'];
                    $obj->B20=$rs_I->fields['B20'];
                    $obj->B21=$rs_I->fields['B21'];
                    $obj->B22=$rs_I->fields['B22'];
                    $obj->B23=$rs_I->fields['B23'];
                    $obj->B24=$rs_I->fields['B24'];
                    $obj->B25=$rs_I->fields['B25'];
                    $obj->C01=$rs_I->fields['C01'];
                    $obj->C02=$rs_I->fields['C02'];
                    $obj->C03=$rs_I->fields['C03'];
                    $obj->C04=$rs_I->fields['C04'];
                    $obj->C05=$rs_I->fields['C05'];
                    $obj->C06=$rs_I->fields['C06'];
                    $obj->C07=$rs_I->fields['C07'];
                    $obj->C08=$rs_I->fields['C08'];
                    $obj->C09=$rs_I->fields['C09'];
                    $obj->C10=$rs_I->fields['C10'];
                    $obj->C11=$rs_I->fields['C11'];
                    $obj->C12=$rs_I->fields['C12'];
                    $obj->C13=$rs_I->fields['C13'];
                    $obj->C14=$rs_I->fields['C14'];
                    $obj->C15=$rs_I->fields['C15'];
                    $obj->C16=$rs_I->fields['C16'];
                    $obj->C17=$rs_I->fields['C17'];
                    $obj->C18=$rs_I->fields['C18'];
                    $obj->C19=$rs_I->fields['C19'];
                    $obj->C20=$rs_I->fields['C20'];
                    $obj->C21=$rs_I->fields['C21'];
                    $obj->C22=$rs_I->fields['C22'];
                    $obj->C23=$rs_I->fields['C23'];
                    $obj->C24=$rs_I->fields['C24'];
                    $obj->C25=$rs_I->fields['C25'];


                 	$SQL_exec = SQL_InsertFromObject("transaksi_hemovingdetil", $obj);		
                   $conn->execute($SQL_exec);
                    $rs_I->Movenext();  
                }   
                   
             

			$conn->CommitTrans();	
		} catch (exception $e) {
			$conn->RollbackTrans();
			die("Error DB:\n\n". $e->GetMessage());	
		}
	

}  catch (exception $e) {
	print $e->GetMessage();	
}

	
			unset($obj);
			$obj->failed  	= $failed;
			$obj->post  	= $POSTVALUE;
			$obj->message 	= $POSTMSG;
			$data = array($obj);		
			
			
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

 
/* ********************************************************************************************************8 */

	 function SQL_UpdateFromObject($tablename, $obj, $criteria) {
		if (!is_object($obj)) return;
		foreach ( $obj as $name => $value ) {
			if (is_object($value)) {
				$value = "0";
			}
			
			$val = "'$value'";
			if ($val=="'__DBNULL__'") {
				$val = "NULL";
			}			
			$updates[] = "$name = $val";		
		}		
		
		$_UPDATES = implode(", ", $updates);
		$SQL	 = "UPDATE $tablename ";
		$SQL	.= "SET ";
		$SQL 	.= $_UPDATES;
		
		if ($criteria) {
			$SQL .= " WHERE $criteria ";
		}
	
		return $SQL;
			
	}
	
	

	 function SQL_InsertFromObject($tablename, $obj) {
		if (!is_object($obj)) {
			return;
		}
	
		foreach ( $obj as $name => $value ) {
			if (is_object($value)) {
				$value = "";
			}
			$fields[] = $name; 	
			$data[]	  = $value;	
		}
	

		
		$_FIELDS  = implode(", ", $fields);
		$_VALUES  = implode("', '", $data);
		$SQL	 = " INSERT INTO $tablename ";
		$SQL	.= " ($_FIELDS) "; 
		$SQL	.= " VALUES ";
		$SQL	.= " ('$_VALUES') ";		
		
		return $SQL;	
	}


	function SQL_InsertFromArray($tablename, $data) {
		if (!is_array($data)) return;
		while (list($name, $value) = each($data)) {
			$fields[] = $name; 	
		}
		
		$_FIELDS  = implode(", ", $fields);
		$_VALUES  = implode("', '", $data);
		$SQL	 = " INSERT INTO $tablename ";
		$SQL	.= " ($_FIELDS) "; 
		$SQL	.= " VALUES ";
		$SQL	.= " ('$_VALUES') ";		
		
		return $SQL;	
	}
	

?> 