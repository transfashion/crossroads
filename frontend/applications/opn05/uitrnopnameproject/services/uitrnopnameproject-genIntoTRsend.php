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

			/* cari kode region nya */
			$sql = "SELECT * FROM transaksi_opnameproject WHERE opnameproject_id='$opnameproject_id'";
			$rs = $conn->Execute($sql);
			$region_id = $rs->fields['region_id'];
			$opnameproject_descr = $rs->fields['opnameproject_descr'];
            
            
			/* kosongkan dulu summarynya, dan isi lagi */
			$sql = "	DELETE FROM transaksi_opnameprojectsum2 WHERE opnameproject_id='$opnameproject_id'";
			$conn->Execute($sql);
			
			$sql = "
				
						  SELECT opnameproject_id='$opnameproject_id',
						  opnameprojectsum_line = 10*ROW_NUMBER() OVER (ORDER BY B.barcode),
					      barcode=B.barcode, 
						  qty=SUM(item_qty)
						INTO #temp1
						FROM transaksi_opname A inner join transaksi_opnamedetil B
						     on A.opname_id = B.opname_id
						WHERE A.opnameproject_id='$opnameproject_id'
						GROUP BY B.barcode
 
						INSERT INTO transaksi_opnameprojectsum2
						(opnameproject_id,opnameprojectsum_line,heinv_id,heinvitem_size, heinvitem_colnum,qty,qty_reserved)
						SELECT A.opnameproject_id,
						opnameprojectsum_line =   10*ROW_NUMBER() OVER (ORDER BY B.heinv_id),
						heinv_id = ISNULL(B.heinv_id,A.barcode),
						heinvitem_size = ISNULL(B.heinvitem_size,''),
						heinvitem_colnum = ISNULL(B.heinvitem_colnum,''),
						qty = SUM(qty),
						qty_reserved = 0
						FROM #temp1 A left join master_heinvitem B on 
						A.barcode = B.heinvitem_barcode 
						GROUP by A.opnameproject_id,B.heinv_id,
						B.heinvitem_size,
						B.heinvitem_colnum,
						A.barcode
						DROP TABLE #temp1
							";			
				      $conn->Execute($sql);



			
			/* ksongkan log */
			$sql = "DELETE FROM transaksi_opnameprojectsummovinglog WHERE opnameproject_id='$opnameproject_id'";	
			$conn->Execute($sql);
			
			
			$sql = "SELECT * FROM transaksi_opnameprojectmoving WHERE opnameprojectmoving_id='$opnameproject_id' ";
			$rs  = $conn->Execute($sql);
			$arr_hemoving_id = array();
			while (!$rs->EOF) {
				$_id =  $rs->fields['hemoving_id'];
				$arr_hemoving_id[] = $rs->fields['hemoving_id'];
				$rs->MoveNext();
			}
			
			
			foreach ($arr_hemoving_id as $hemoving_id) {

				$hemoving_id = trim($hemoving_id); 
				if ($hemoving_id) {

					/* kosongkan data unpivot */
					$sql = " DELETE FROM transaksi_hemovingdetilunpivot2 WHERE hemoving_id = '$hemoving_id' ";
 					$conn->Execute($sql);
 
					$sql = "	
                    
                    DECLARE @hemoving_id AS varchar(30);
                    SET @hemoving_id = '$hemoving_id'
 

                	SET NOCOUNT ON;
                
                	DECLARE @region_id AS varchar(30);
                	SET @region_id   = (SELECT region_id FROM transaksi_hemoving WHERE hemoving_id = @hemoving_id);
                
                	SELECT heinv_id, hemovingdetil_line, colname, heinvitem_colnum=substring(colname,2,2), qty
                	INTO #TEMP_UNPIVOT
                	FROM (
                			select  
                			heinv_id, hemovingdetil_line,
                			A01,A02,A03,A04,A05,A06,A07,A08,A09,A10,A11,A12,A13,A14,A15,A16,A17,A18,A19,A20,A21,A22,A23,A24,A25
                			from transaksi_hemovingdetil where hemoving_id = @hemoving_id
                	) X
                	UNPIVOT
                	(qty FOR colname IN (A01,A02,A03,A04,A05,A06,A07,A08,A09,A10,A11,A12,A13,A14,A15,A16,A17,A18,A19,A20,A21,A22,A23,A24,A25))
                	AS Y
                	WHERE qty > 0

 
                	SELECT hemoving_id=@hemoving_id,
                	hemovingdetil_line, heinv_id, colname, heinvitem_colnum, qty
                	FROM #TEMP_UNPIVOT
                	DROP TABLE #TEMP_UNPIVOT

                     ";
                     
                     
                     
                     
                     
					$rs = $conn->Execute($sql);
					$qty_reserved = 0;
                    
               
                    
					while (!$rs->EOF) {
						$hemovingdetil_line = $rs->fields['hemovingdetil_line'];
						$heinv_id           = $rs->fields['heinv_id'];
						$colname	        = $rs->fields['colname'];
						$heinvitem_colnum   = $rs->fields['heinvitem_colnum'];
						$qty_hemoving       = $rs->fields['qty'];

					
						/* cek ke project sum */
						$sql = "
								SELECT * FROM  transaksi_opnameprojectsum2 WHERE opnameproject_id='$opnameproject_id' 
								AND 
								heinv_id = '$heinv_id' AND heinvitem_colnum = '$heinvitem_colnum'";
								
						$rsI = $conn->Execute($sql);
						$opnameprojectsum_line = $rs->fields['opnameprojectsum_line'];
						$qty_scanned = (int) $rsI->fields['qty'];
						$qty_reserved = (int) $rsI->fields['qty_reserved'];
						$qty_available = $qty_scanned - $qty_reserved; 

						/* bandingkan qty di rv init, dengan qty yang masih available */
						if ($qty_hemoving<=$qty_available) {
							/* masih tersedia */
							$qty_used = $qty_hemoving;
							
							/* tambah reserve qty current senilai qty used */
							$qty_reserved += $qty_used;
						} else {
							/* nila qty di RV lebih banyak, hasil fisik lebih sedikit */
							$qty_used = $qty_available;
							
							/* tambah reserve qty current senilai qty used */
							$qty_reserved += $qty_used;
						}


						/* masukkan ke transaksi_hemovingdetilunpivot */
						unset($obj);
						$obj->hemoving_id         = $hemoving_id;
						$obj->hemovingdetil_line  = $hemovingdetil_line;
						$obj->heinvitem_colnum    = $heinvitem_colnum;
						$obj->heinv_id            = $heinv_id;
						$obj->qty_A               = $qty_hemoving;
						$obj->qty_B               = $qty_hemoving;
						$obj->qty_C               = $qty_used;
						
						$sql = SQL_InsertFromObject("transaksi_hemovingdetilunpivot2", $obj);
						$conn->Execute($sql);
											
						
			 		
												/* ubah nilai kolom Cxx dari RV dan RV line current */
						$COLNAMEDAT = "B" . $heinvitem_colnum;
						$sql = "UPDATE transaksi_hemovingdetil 
								  SET $COLNAMEDAT = '$qty_used'
								  WHERE hemoving_id = '$hemoving_id' AND hemovingdetil_line = '$hemovingdetil_line'
						";
                        
                        
					 	$conn->Execute($sql);
					 	
					 	
						/* reserved data */						
						$sql = "UPDATE transaksi_opnameprojectsum2 SET qty_reserved='$qty_reserved'  WHERE opnameproject_id='$opnameproject_id' AND heinv_id='$heinv_id' and  heinvitem_colnum =  '$heinvitem_colnum'";
						$conn->Execute($sql);

						/* masukkan ke log */
						$sql  = "SELECT line=MAX(opnameprojectsummovinglog_line) FROM transaksi_opnameprojectsummovinglog WHERE opnameproject_id='$opnameproject_id' AND opnameprojectsum_line='$opnameprojectsum_line'   ";
						$rsI  = $conn->Execute($sql);
						$opnameprojectsummovinglog_line = !$rsI->recordCount() ? 1 :  1 + $rsI->fields['line'];						

						unset($obj);
						$obj->opnameproject_id = $opnameproject_id;
						$obj->opnameprojectsummovinglog_line = $opnameprojectsummovinglog_line;
						$obj->opnameprojectsum_line = $opnameprojectsum_line;
						$obj->hemoving_id = $hemoving_id;
						$obj->hemovingdetil_line = $hemovingdetil_line;
						$obj->hemovingdetil_colnum = $heinvitem_colnum;
						$obj->hemovingdetil_qty = $qty_hemoving;
						$obj->qtyused = $qty_used;
						
						$sql = SQL_InsertFromObject(transaksi_opnameprojectsummovinglog, $obj);
						$conn->Execute($sql);

						$rs->MoveNext();
						
					}								
				
				}
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