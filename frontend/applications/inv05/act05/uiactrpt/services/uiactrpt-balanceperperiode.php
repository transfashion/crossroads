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
	


		$region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$year_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'cmbYear', '', "{criteria_value}");
		$month_id     = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'cmbMonth',   '', "{criteria_value}");
		$mode_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_mode_id', '', "{criteria_value}");
		
}




	$_year  = 1*$year_id;
	$_month = 1*$month_id;
	$_day   = 1;


	
	if (checkdate($_month, $_day, $_year)) {
		/* ambil tanggal terakhir di bulan tersebut */
		$sql = "
			SET NOCOUNT ON;
			DECLARE @dt as smalldatetime;
			DECLARE @newdt as smalldatetime;
			SET @dt = '$_year-$_month-$_day';
			SET @newdt = DATEADD(DAY, -1, CAST((CAST(YEAR(DATEADD(MONTH, 1, @dt)) as varchar) + '-' + CAST(MONTH(DATEADD(MONTH, 1, @dt)) as varchar) + '-1') as smalldatetime));
			SET NOCOUNT OFF;
			SELECT [DAY]=DAY(@newdt), [MONTH]=MONTH(@newdt), [YEAR]=YEAR(@newdt)		
		";
		$rs = $conn->Execute($sql);
		$_day  = $rs->fields['DAY'];
		$_month = $rs->fields['MONTH'];
		$_year   = $rs->fields['YEAR'];

	}  

  
	
	if (checkdate($_month, $_day, $_year)) {
		/* ambil tanggal terakhir di bulan tersebut */
		$sql = "
			SET NOCOUNT ON;
			DECLARE @dt as smalldatetime;
			DECLARE @newdt as smalldatetime;
			SET @dt = '$_year-$_month-$_day';
			SET @newdt = DATEADD(DAY, -1, CAST((CAST(YEAR(DATEADD(MONTH, 1, @dt)) as varchar) + '-' + CAST(MONTH(DATEADD(MONTH, 1, @dt)) as varchar) + '-1') as smalldatetime));
			SET NOCOUNT OFF;
			SELECT [DAY]=DAY(@newdt), [MONTH]=MONTH(@newdt), [YEAR]=YEAR(@newdt)		
		";
		$rs = $conn->Execute($sql);
		$_day  = $rs->fields['DAY'];
		$_month = $rs->fields['MONTH'];
		$_year   = $rs->fields['YEAR'];

	}  

/* DATABASE */
  
    
                            //201006-01500
	                       $heinvclosingstatus_id = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT)."-".$region_id;
                           $sql = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$heinvclosingstatus_id' ";
	                       $rs  = $conn->Execute($sql);

                        	if ($rs->recordCount()) 
                            {
                        		    $heinvclosingstatus_iscompleted = 1*$rs->fields['heinvclosingstatus_iscompleted'];
                                    $conn->Execute("DELETE FROM transaksi_heinvclosingstatusdetil WHERE heinvclosingstatus_id='$heinvclosingstatus_id'");
    		                        $conn->Execute("DELETE FROM transaksi_heinvclosingstatus      WHERE heinvclosingstatus_id='$heinvclosingstatus_id'");
                                    
                        	} 
                            



	/* Mulai Generate Data */
	$sql = "SELECT id=cast(newid() as varchar(50)) ";
	$rs  = $conn->Execute($sql);
	$cacheid = $rs->fields['id'];
	$date_start = "$_year-$_month-1"; 
	$date_end   = "$_year-$_month-$_day"; 
	

	$sql = "DELETE FROM cache_heinvsummary WHERE cacheid='$cacheid'";
	$conn->Execute($sql);

        $SQLBranch ="SELECT * from master_regionbranch where region_id = '$region_id'";
        $rsBranch = $conn->execute($SQLBranch);
        
            $conn->BeginTrans();
            WHILE (!$rsBranch->EOF)
            {
                
                $branch_id = $rsBranch->fields['branch_id'];
                
                $SQLCTG = "SELECT * FROM master_heinvctg WHERE region_id = '$region_id'";
                $rsCTG = $conn->Execute ($SQLCTG);
                
                  WHILE (!$rsCTG->EOF)
                    {
                        $heinvctg_id = $rsCTG->fields['heinvctg_id'];
                        $heinvgro_id = $rsCTG->fields['heinvgro_id'];
                        
                        
                        $SQLSEASON = "SELECT season_id from master_season";
                        $rsSeason = $conn->execute($SQLSEASON);
                        
                        WHILE (!$rsSeason->EOF)
                            {
                        
                        $season_id = $rsSeason->fields['season_id'];
                        
    	                   $sqlI = "
                			SET NOCOUNT ON;
    			
            				declare @date_start as smalldatetime;
            				declare @date_end as smalldatetime;
            				declare @region_id as varchar(5);
            				declare @heinvgro_id as varchar(30);
            				declare @heinvctg_id as varchar(30);
            				declare @season_id as varchar(30);
            				
            				SET @region_id = '$region_id'
            				SET @heinvgro_id = '$heinvgro_id';
            				SET @heinvctg_id = '$heinvctg_id';
            				SET @season_id = '$season_id';				
            				SET @date_start = '$date_start';
            				SET @date_end   = '$date_end';
            				
            				EXEC inv05he_RptSummaryAllBranchCtg @date_start, @date_end, @region_id, @heinvgro_id, @heinvctg_id, @season_id, 0, NULL, 0";	       
                            $rsB  = $conn->Execute($sqlI);
                                            
                        	$data = array();
                        	$ERROR = false;
                        
                                    	try {
                                    	
                                    		$totalCount = $rsB->recordCount();
                                    		$cacheid    = $rsB->fields['cacheid'];
                                    		
                                    		if ($totalCount>100) {
                                    			$jumlah_halaman = 10;
                                    		} else {
                                    			$jumlah_halaman = 1;
                                    		}
                                    		$limit = ceil($totalCount/$jumlah_halaman);  
                                    		for ($i=0; $i<$jumlah_halaman; $i++) {
                                    			unset($obj);
                                    			$start = $i*$limit; 
                                    			$obj->ids = "$cacheid|$jumlah_halaman|$limit|$start";
                                    			$data[] = $obj;
                                    		}
                                    	
                                    	} catch (exception $e) {
                                    		$ERROR = $e->GetMessage();	
                                    	}
                                    	
                             $rsSeason->MoveNext();
                         }      
                     $rsCTG->MoveNext();
                 }   
                    $rsBranch->MoveNext();
            }
    
    
    
     
 

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data =  $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));
 
?> 