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
	

        $closingyear   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'closingyear', '', "{criteria_value}");
        $closingmonth   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'closingmonth', '', "{criteria_value}");
		$region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'objRegion', '', "{criteria_value}");
		$closingstatus = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'objclosingstatus', '', "{criteria_value}");
		
}

if (strlen($closingmonth)==1)
{
    $closingmonth = '0' .$closingmonth;
}


$_year = $closingyear;
$_month = $closingmonth;

$heinvclosingstatus_id = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT)."-".$region_id;

$sql = "SELECT * FROM transaksi_heinvclosingstatus WHERE heinvclosingstatus_id='$heinvclosingstatus_id' ";
$rs  = $conn->Execute($sql);	


$heinvclosingstatus_iscompleted  =1*$rs->fields['heinvclosingstatus_iscompleted'];
    
                         
print '->>  CLOSINGSTATUS : ' . $heinvclosingstatus_iscompleted;


die();


switch ($closingstatus) 
{
    case "OPEN":
    
        break;
    CASE "CLOSE" :
    break;
}


















$id = explode("|",$__ID);

$region_id = $id[0];
$year_id = $id[1];
$month_id = $id[2];


	$_year  = $year_id;
	$_month = $month_id;
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

/* DATABASE */
    try
    {
    
    
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
	           $sqlI = "
            			SET NOCOUNT ON;
            		
            			declare @date_start as smalldatetime;
            			declare @date_end as smalldatetime;
            			declare @region_id as varchar(5);
            			declare @branch_id as varchar(7);
            			declare @FLATMODE as tinyint;
            			declare @CACHEID as varchar(50);
            			
            
            			SET @date_start = '$date_start';
            			SET @date_end   = '$date_end';
            			
            			SET @region_id = '$region_id'
            			SET @branch_id = '$branch_id';
            			
            			SET @FLATMODE=1;
            			SET @CACHEID = '$cacheid'
            
            			EXEC inv05he_RptSummaryByBranch @date_start, @date_end, @region_id, @branch_id, @FLATMODE, @CACHEID, 1";
                        
                        
                      $rsB  = $conn->Execute($sqlI);
                      unset($obj);
                      $obj->saldo_id =  $_year.str_pad($_month, 2, "0", STR_PAD_LEFT).str_pad($_day, 2, "0", STR_PAD_LEFT)."-".$region_id."-".$branch_id;
		              $obj->saldo_createby = 'system.saldogen';
		              $obj->region_id = $region_id;
		              $obj->branch_id = $branch_id;
       	              
                      $sqlSaldo = "SELECT * FROM transaksi_hesaldo WHERE saldo_id='".$obj->saldo_id."'";
		              $rsSaldo  = $conn->Execute($sqlSaldo);
                       
                    	if (!$rsSaldo->recordCount()) 
                        {
			             /* insert */
			             $SQL = SQLUTIL::SQL_InsertFromObject('transaksi_hesaldo', $obj);
                         
                        } 
                        else 
                        {
			             /* update, jika buka saldo system */
			                 if (!$rs->fields['saldo_issystembegin']) 
                             {
				                $SQL = SQL_UpdateFromObject('transaksi_hesaldo', $obj, "saldo_id='".$obj->saldo_id."'");                                
			                 } 
                             else 
                             {
				               throw new Exception($obj->saldo_id . " is system saldo, and cannot be modified.\n\n");
                             }		
                        }
                    
                    	$conn->Execute($SQL);
                        
                        
                        
                        $conn->Execute("DELETE FROM transaksi_hesaldodetil WHERE saldo_id='".$obj->saldo_id."'");		
	                    $sqlCache = "SELECT * from dbo.cache_heinvsummary where cacheid='$cacheid' AND branch_id='$branch_id'";
	                    $rsCache = $conn->Execute($sqlCache);
                        	$linenum = 0;
                        	WHILE (!$rsCache->EOF) 
                            {
                                 	$linenum++;
                                   	unset($obj);
                        			$obj->saldo_id = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT).str_pad($_day, 2, "0", STR_PAD_LEFT)."-".$region_id."-".$branch_id;
                        			$obj->saldodetil_line = $linenum;
                        			$obj->saldodetil_begin = $rsCache->fields['BEG'];
                        			$obj->saldodetil_recv = $rsCache->fields['RV'];
                        			$obj->saldodetil_trou = $rsCache->fields['TOUT'];
                        			$obj->saldodetil_trin = $rsCache->fields['TIN'];
                        			$obj->saldodetil_trtran = $rsCache->fields['TTS'];
                        			$obj->saldodetil_sales = $rsCache->fields['SL'];
                        			$obj->saldodetil_do = $rsCache->fields['DO'];
                        			$obj->saldodetil_adj = $rsCache->fields['AJ'];
                        			$obj->saldodetil_as = $rsCache->fields['AS'];
                        			$obj->saldodetil_oth = $rsCache->fields['OTHER'];
                        			$obj->saldodetil_end = $rsCache->fields['END'];
                        			$obj->heinv_id = $rsCache->fields['heinv_id'];
                        			$obj->C01 = $rsCache->fields['C01'];
                        			$obj->C02 = $rsCache->fields['C02'];
                        			$obj->C03 = $rsCache->fields['C03'];
                        			$obj->C04 = $rsCache->fields['C04'];
                        			$obj->C05 = $rsCache->fields['C05'];
                        			$obj->C06 = $rsCache->fields['C06'];
                        			$obj->C07 = $rsCache->fields['C07'];
                        			$obj->C08 = $rsCache->fields['C08'];
                        			$obj->C09 = $rsCache->fields['C09'];
                        			$obj->C10 = $rsCache->fields['C10'];
                        			$obj->C11 = $rsCache->fields['C11'];
                        			$obj->C12 = $rsCache->fields['C12'];
                        			$obj->C13 = $rsCache->fields['C13'];
                        			$obj->C14 = $rsCache->fields['C14'];
                        			$obj->C15 = $rsCache->fields['C15'];
                        			$obj->C16 = $rsCache->fields['C16'];
                        			$obj->C17 = $rsCache->fields['C17'];
                        			$obj->C18 = $rsCache->fields['C18'];
                        			$obj->C19 = $rsCache->fields['C19'];
                        			$obj->C20 = $rsCache->fields['C20'];
                        			$obj->C21 = $rsCache->fields['C21'];
                        			$obj->C22 = $rsCache->fields['C22'];
                        			$obj->C23 = $rsCache->fields['C23'];
                        			$obj->C24 = $rsCache->fields['C24'];
                        			$obj->C25 = $rsCache->fields['C25'];
                        			
                        			
                        			try {
                        				$SQL = SQLUTIL::SQL_InsertFromObject('transaksi_hesaldodetil', $obj);			
                        				$conn->Execute($SQL);
                        			} catch (exception $e) {
                        				print "Error.\n";
                        				throw new Exception($e->GetMessage());	
                        			}
                        			
                        
                                $rsCache->MoveNext();
                            }
    
                    $rsBranch->MoveNext();
            }
    
    
    
    
    
  
    
    	/* Hitung Costing */
	$sql = "

			SET NOCOUNT ON;
			
			declare @date_start as smalldatetime;
			declare @date_end as smalldatetime;
			declare @region_id as varchar(5);
			
			SET @date_start = '$date_start';
			SET @date_end   = '$date_end';
			SET @region_id = '$region_id'
			
			
			SELECT
			A.hemoving_id, B.hemovingdetil_line, B.heinv_name,
			B.heinv_id,
			[RV]=B.C01+B.C02+B.C03+B.C04+B.C05+B.C06+B.C07+B.C08+B.C09+B.C10+B.C11+B.C12+B.C13+B.C14+B.C15+B.C16+B.C17+B.C18+B.C19+B.C20+B.C21+B.C22+B.C23+B.C24+B.C25,
			A.currency_rate,
			B.heinv_price,
			B.heinv_disc,
			valueperpiece = A.currency_rate*(B.heinv_price*((100-B.heinv_disc)/100)) 
			INTO #TEMP_MV_109
			from transaksi_hemoving A inner join transaksi_hemovingdetil B
			on A.hemoving_id = B.hemoving_id
			WHERE
			hemovingtype_id = 'RV'
			AND region_id = @region_id
			AND hemoving_isdisabled=0
			AND hemoving_isrecv=1
			AND hemoving_ispost=1
			AND convert(varchar(10),A.hemoving_date_to,120)>=convert(varchar(10),@date_start,120)
			AND convert(varchar(10),A.hemoving_date_to,120)<=convert(varchar(10),@date_end,120)
			
			
			--SELECT * FROM #TEMP_MV_109 WHERE heinv_id='TM10070030900'
			SELECT
			heinv_id,
			[RV]=SUM([RV]),
			[VALUEAVG] = SUM([RV]*[valueperpiece]) / SUM([RV])
			INTO #TEMP_MV_110
			from #TEMP_MV_109
			GROUP BY heinv_id
			HAVING SUM([RV])<>0
			
			
			-- AMBIL DATA ENDING BULAN LALU
			DECLARE @lastmonth as smalldatetime;
			DECLARE @last_heinvclosingstatus_id as varchar(12);
			
			
			SET @lastmonth = DATEADD(month , -1 ,@date_end)
			SET @last_heinvclosingstatus_id = CAST(YEAR(@lastmonth) as varchar) + dbo.f_zerofill(CAST(MONTH(@lastmonth) as varchar),2) + '-' + @region_id;
			
			
			select heinv_id, 
			[BEG] = SUM(cast([BEG] as decimal)), 
			[BEG_VAL] = isnull((SELECT END_VAL FROM transaksi_heinvclosingstatusdetil WHERE heinvclosingstatus_id=@last_heinvclosingstatus_id AND heinv_id=A.heinv_id) ,0),
			[RV] = SUM(cast([RV] as decimal)),
			[RV_VAL] = SUM(cast([RV] as decimal)) * isnull((SELECT [VALUEAVG] FROM #TEMP_MV_110 WHERE heinv_id = A.heinv_id), 0),
			[TTS] = SUM(cast([TTS] as decimal)),
			[END] = SUM(cast([END] as decimal))
			INTO #TEMP_SUM_199
			from dbo.cache_heinvsummary A 
			where cacheid = '$cacheid'
			group by heinv_id
			
			SET NOCOUNT OFF;
			
			select 
			heinv_id,
			A.[BEG],
			A.[BEG_VAL],
			A.[RV],
			A.[RV_VAL],
			COST = CASE WHEN (A.[BEG] + A.[RV]) <> 0 THEN (A.[BEG_VAL]+A.[RV_VAL]) / (A.[BEG] + A.[RV]) ELSE 0 END,
			A.[TTS],
			A.[END],
			[END_VAL] = CASE WHEN (A.[BEG] + A.[RV]) <> 0 THEN A.[END] * ((A.[BEG_VAL]+A.[RV_VAL]) / (A.[BEG] + A.[RV])) ELSE 0 END
			FROM #TEMP_SUM_199 A
			
			
			SET NOCOUNT ON;
			
			DROP TABLE #TEMP_MV_109;
			DROP TABLE #TEMP_MV_110;
			DROP TABLE #TEMP_SUM_199;
	
	";

print $sql;

	/* Masukkan data header closing summary transaksi_heinvclosingstatus */
	unset($obj);
	$obj->heinvclosingstatus_id = $heinvclosingstatus_id;
	$obj->heinvclosingstatus_date = $date_end;
	$obj->heinvclosingstatus_createby = 'system.saldogen';
	$SQL = SQLUTIL::SQL_InsertFromObject('transaksi_heinvclosingstatus', $obj);
	$conn->Execute($SQL);
	
    
    
    
    
    
    
    


	/* Data Costing */
	$BEG = 0; $BEG_VAL=0; $RV=0; $RV_VAL=0; $END=0; $END_VAL = 0;
	$TTS = 0; $TTS_VAL=0; $TOTAL_QTY=0; $TOTAL_VALUE=0;
	$linenum = 0;
	$rs = $conn->Execute($sql);
	$total = $rs->recordCount();
	//print "Total data : ".$rs->recordCount()." rows\n";
	while (!$rs->EOF) {
		$linenum++;
		unset($obj);
		$obj->heinvclosingstatus_id = $heinvclosingstatus_id;
		$obj->heinvclosingstatusdetil_line = $linenum;
		$obj->heinv_id 	= $rs->fields['heinv_id'];
		$obj->BEG 		= (int) $rs->fields['BEG'];
		$obj->BEG_VAL 	= (float) $rs->fields['BEG_VAL'];
		$obj->RV 		= (int) $rs->fields['RV'];
		$obj->RV_VAL 	= (float) $rs->fields['RV_VAL'];
		$obj->COST		= (float) $rs->fields['COST'];
		$obj->TTS		= (int) $rs->fields['TTS'];
		$obj->END		= (int) $rs->fields['END'];
		$obj->END_VAL	= (float) $rs->fields['END_VAL'];


		/* COST CORRECTION */
		$sqlc = "SELECT * FROM transaksi_heinvclosingstatuscostcorrection WHERE heinvclosingstatus_id='$heinvclosingstatus_id' AND heinv_id='".$obj->heinv_id."'";
		$rsC  = $conn->Execute($sqlc);
		if ($rsC->recordCount()) {
			$obj->COST		= (float) $rsC->fields['COST'];
			$obj->END_VAL	= $obj->COST * $obj->END;
		}
		
		/* jika COST masih nol tapi END nya ada, baca dari master_heinv */
		if (! ((float) $obj->COST) ) {
			$sqlc = "	select TOP 1 * from dbo.transaksi_heinvclosingstatusdetil
						where 
						heinv_id = '".$obj->heinv_id."'
						and COST<>0
						order by heinvclosingstatus_id DESC";
	
			$rsC  = $conn->Execute($sqlc);
			if ($rsC->recordCount()) {
				$obj->COST		= (float) $rsC->fields['COST'];
				$obj->END_VAL	= $obj->COST * $obj->END;
			}						
		
		}
		

		/* INVENTORY IN TRANSIT */
		$obj->TTS_VAL		= (float) $obj->COST * (float) $obj->TTS;		
		$obj->TOTAL_QTY		= (int) $obj->TTS +  (int) $obj->END;
		$obj->TOTAL_VALUE	= (int) $obj->TTS_VAL +  (int) $obj->END_VAL;		
		
		
		$SQL = SQLUTIL::SQL_InsertFromObject('transaksi_heinvclosingstatusdetil', $obj);
		$conn->Execute($SQL);		
		
		
		$BEG 		= $BEG + $obj->BEG;
		$BEG_VAL 	= $BEG_VAL + $obj->BEG_VAL;
		$RV  		= $RV + $obj->RV;
		$RV_VAL		= $RV_VAL + $obj->RV_VAL;
		$END		= $END + $obj->END;
		$END_VAL	= $END_VAL + $obj->END_VAL;
		
		$TTS 		= $TTS + $obj->TTS;
		$TTS_VAL	= $TTS_VAL + $obj->TTS_VAL;
		$TOTAL_QTY	= $TOTAL_QTY + $obj->TOTAL_QTY;
		$TOTAL_VALUE = $TOTAL_VALUE + $obj->TOTAL_VALUE; 

		/* UPDATE heinvsaldodetil */
		$saldo_id = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT).str_pad($_day, 2, "0", STR_PAD_LEFT)."-".$region_id."-";
		$SQL = "UPDATE transaksi_hesaldodetil 
		        SET 
				saldodetil_endvalue = saldodetil_end * ".$obj->COST."
		        WHERE saldo_id LIKE '$saldo_id%' AND heinv_id='".$obj->heinv_id."'";
		$conn->Execute($SQL);

		

		$rs->MoveNext();
		
	}
	
    

/* --print "Check Saldo, apakah ada yang minus... ";  */
	

	$globalsaldo_id         = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT).str_pad($_day, 2, "0", STR_PAD_LEFT)							 ."-".$region_id;	

	
	$sql = "SELECT * FROM transaksi_hesaldodetil WHERE saldo_id LIKE '$globalsaldo_id%' AND saldodetil_end < 0 ";
	$rs  = $conn->Execute($sql);
	$total = $rs->recordCount();
    
	if ($total) {

		while (!$rs->EOF) {
			$heinv_id = $rs->fields['heinv_id'];
			$saldodetil_end = $rs->fields['saldodetil_end'];
			$saldo_id = $rs->fields['saldo_id'];
		
        
            $obj->heinv_id = $heinv_id;
            $obj->saldodetil_end = $saldodetil_end;
            $obj->saldo_id = $saldo_id;
            
        	$data[] = $obj;
			$rs->MoveNext();		
		}
			
	} 





    } 
    catch (exception $e) 
    {
	$conn->RollbackTrans();
	print "\n\n\nPHP Error\n"."----------------------------------------------------------------\n".$e->GetMessage();	
    }


    
    
 

 

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data =  $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));

  


?> 