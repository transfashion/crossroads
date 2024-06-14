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
    
 
        $tanggal = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'tanggal', '', "{criteria_value}");
        
        $periode_id   = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'periode_id', '', "{criteria_value}");
  		$acc_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'acc_id', '', "{criteria_value}");
        $region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'region_id', '', "{criteria_value}");
        $branch_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'branch_id', '', "{criteria_value}");
        $strukturunit_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'strukturunit_id', '', "{criteria_value}");
        $amount   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'amount', '', "{criteria_value}");
        $type   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'type', '', "{criteria_value}");
        $filename   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'FILENAME_DB', '', "{criteria_value}");
  		
}


$today =date("Y-m-d H:i:s");
 
   
            unset ($obj);
            $sukses = 1;
            $i=0;
       
           
     
            
        
        
            $sql_P = "SELECT TOP 1 periode_isclosed FROM master_periode WHERE periode_id = '$periode_id'";
            $rs_P = $conn->execute($sql_P);
            
            $periode_isclosed = $rs_P->fields['periode_isclosed'];
            
            if ($periode_isclosed ==1 )
            {
                   $errorMsg = "Periode Sudah Di Close, Jurnal Tidak Bisa Disimpan";
                   $sukses = 0;
            }
            
            
             $sql_ID = "	
            DECLARE @jurnal_id as nvarchar(12)
            EXEC cp_sequencer_jurnal 'MGP', 'SA',  @jurnal_id OUTPUT
            SELECT @jurnal_id as jurnal_id
            ";
        
           
            $rsJ = $conn->execute($sql_ID);
            $jurnal_id = $rsJ->fields['jurnal_id'];
            
            if (!$jurnal_id)
            {
                   $errorMsg = "Gagal Membuat Jurnal ID, Jurnal Tidak Bisa Disimpan";
                   $sukses = 0;
            }
 
            
                   $year = substr($periode_id,0,2);
                   $bulan = substr($periode_id,2,2);
                   $bulan =  str_pad($bulan,2,"0",STR_PAD_LEFT);
                   $bookdate =  '20' . $year . "-" . $bulan ."-". $tanggal;
                   
            
          
   
                    
                    
       if ($sukses==1)
       {
        
                 
                   
                   
                   
                   $jurnal_descr = $type .  ' - Penjualan ' . $year . '/' . $bulan . '/' . $tanggal;
                   
                  
                   $sql = "SELECT * FROM temp_sales WHERE type = '$type' and periode_id = '$periode_id' AND bookdate = '$bookdate'";
                   $rs = $conn->execute($sql);
                   $totalcount = $rs->recordcount();
                   
                   if ($totalcount>0)
                   {
                   
                           unset ($obj);
                           $obj->jurnal_id = $jurnal_id;
                           $obj->jurnal_bookdate = $bookdate;
                           $obj->jurnal_descr = $jurnal_descr;
                           $obj->jurnal_isposted = 1;
                           $obj->jurnal_createby = 'system';
                           $obj->jurnal_createdate = $today ;
                           $obj->jurnal_modifyby =null;
                           $obj->jurnal_modifydate =null;
                           $obj->jurnal_postby ='system';
                           $obj->jurnal_postdate =$today ;
                           $obj->jurnal_duedate =$today ;
                           $obj->jurnal_faktur ='';
                           $obj->jurnal_source ='SA-Manual';
                           $obj->jurnaltype_id ='SA';
                           $obj->channel_id ='MGP';
                           $obj->region_id ='00100';
                           $obj->branch_id ='0000100';
                           $obj->strukturunit_id ='0';
                           $obj->rekanan_id ='1020176';
                           $obj->sub1_id ='0';
                           $obj->sub2_id ='0';
                           $obj->currency_id ='IDR';
                           $obj->currency_rate =1;                   
                           $obj->periode_id =$periode_id;
                           $obj->acc_id ='3010012';                   
                           
                            $SQL = SQLUTIL::SQL_InsertFromObject('transaksi_jurnal', $obj);
                            $conn->Execute($SQL);
                             $obj->sukses = 1;
                                $obj->errorMsg = "";
                            $data[] = $obj;
                            
                          
                            
                            
                           unset($obj);
                           $obj->log_event = $jurnal_id;
                           $obj->log_descr = 'Insert jurnal ' . $jurnal_id;;
                           $obj->jurnal_id = '$jurnal_id';
                           $obj->username = $username;
                           $SQL = SQLUTIL::SQL_InsertFromObject('log_jurnal', $obj);
                           $conn->Execute($SQL);
             
                 
                  
                  
                           WHILE (!$rs->EOF)
                           {
                                $i=$i+10;
                                $region_id = $rs->fields['region_id'];
                                $branch_id = $rs->fields['branch_id'];
                                $strukturunit_id = $rs->fields['strukturunit_id'];

                                $acc_id = $rs->fields['acc_id'];
                                
                                $SQL_R = "select region_name FROM master_region WHERE region_id = '$region_id'";
                                $rsR = $conn->execute($SQL_R);
                                $region_name =$rsR->fields['region_name'];
                                
                                $SQL_B = "select branch_name FROM master_branch WHERE branch_id = '$branch_id'";
                                $rsB = $conn->execute($SQL_B);
                                $branch_name =$rsB->fields['branch_name'];
                                
                                
                                $amount = (float) $rs->fields['amount'];
                               
                                
                                
                                /* Masuk ke jurnal detil */
                                unset($obj);
                                $obj->jurnal_id =  $jurnal_id;
                                $obj->jurnaldetil_line =  $i;
                                
                                
                                IF ($amount>0)
                                {
                                    $jurnaldetil_dk = 'D';
                                }
                                else
                                {
                                    $jurnaldetil_dk = 'K';
                                }
                                $jurnal_descr = $type .  ' - Penjualan ' . $year . '/' . $bulan . '/' . $tanggal;
                                $obj->jurnaldetil_dk =  $jurnaldetil_dk;
                                $obj->jurnaldetil_descr =  $jurnal_descr . ' - ' . $region_name . ' - ' . $branch_name;
                                $obj->jurnaldetil_idr = $amount;
                                $obj->jurnaldetil_foreign = 0;
                                $obj->jurnaldetil_foreignrate = 1;
                                $obj->ref_id = null;
                                $obj->ref_line = null;
                                $obj->currency_id = 'IDR';
                                $obj->acc_id =  $acc_id ;
                                $obj->channel_id = 'MGP';
                                $obj->region_id = $region_id;
                                $obj->branch_id = $branch_id;
                                $obj->strukturunit_id = $strukturunit_id;
                                $obj->rekanan_id = '1020176';
                                $obj->sub1_id = '0';
                                $obj->sub2_id = '0';
                                $obj->sub2_id = '0';
                                $obj->tag = null;
                               
                                $SQL = SQLUTIL::SQL_InsertFromObject('transaksi_jurnaldetil', $obj);
                                $conn->Execute($SQL);
                                
                                 
                                $obj->sukses = 1;
                                $obj->errorMsg = "";
                                $data[] = $obj;                     									
                                $rs->MoveNext();
                           }
                        
                      }
                      else
                      {
                        $obj->sukses = 1;
                        $obj->errorMsg = "";
                        $obj->jurnal_id = "";
                        $data[] = $obj;  
                      }
             
             }
        else
        {
            
           unset($obj);
           $obj->sukses = $sukses;
           $obj->errorMsg = $errorMsg;
           $data[] = $obj;  
           
            
        }  
        
           
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>