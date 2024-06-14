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
	
        $region_id   = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'region_id', '', "{criteria_value}");
  		$heinvclosingstatus_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'heinvclosingstatus_id', '', "{criteria_value}");
        $cacheid   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'cacheid', '', "{criteria_value}");
}

    $_year  = substr($heinvclosingstatus_id,0,4);
	$_month = substr($heinvclosingstatus_id,4,2);;
	$_day   = 1;
	
  	         $sql  = "
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
        
        
        
    $date_start = "$_year-$_month-1"; 
	$date_end   = "$_year-$_month-$_day"; 
        
        
        
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


        /* Masukkan data header closing summary transaksi_heinvclosingstatus */
	unset($obj);
	$obj->heinvclosingstatus_id = $heinvclosingstatus_id;
	$obj->heinvclosingstatus_date = $date_end;
	$obj->heinvclosingstatus_createby = 'php.saldogenTB';
	$SQL = SQLUTIL::SQL_InsertFromObject('transaksi_heinvclosingstatus', $obj);
	$conn->Execute($SQL);
    
    
    
    /* Data Costing */
	$BEG = 0; $BEG_VAL=0; $RV=0; $RV_VAL=0; $END=0; $END_VAL = 0;
	$TTS = 0; $TTS_VAL=0; $TOTAL_QTY=0; $TOTAL_VALUE=0;
	$linenum = 0;
	$rs = $conn->Execute($sql);
	$total = $rs->recordCount();
        
        
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
		
		
		/*
		print $obj->heinv_id;
		print "    ";
		print str_pad($obj->BEG, 5, " ", STR_PAD_LEFT);  	
		print str_pad(number_format($obj->BEG_VAL), 15, " ", STR_PAD_LEFT);   	
		print str_pad($obj->RV, 5, " ", STR_PAD_LEFT);  	
		print str_pad(number_format($obj->RV_VAL), 15, " ", STR_PAD_LEFT);   	
		print str_pad(number_format($obj->COST), 15, " ", STR_PAD_LEFT);   	
		print str_pad($obj->END, 5, " ", STR_PAD_LEFT);  	
		print str_pad(number_format($obj->END_VAL), 15, " ", STR_PAD_LEFT);   	
		print "\n";
		*/

		/* UPDATE heinvsaldodetil */
		$saldo_id = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT).str_pad($_day, 2, "0", STR_PAD_LEFT)."-".$region_id."-";
		$SQL = "UPDATE transaksi_hesaldodetil 
		        SET 
				saldodetil_endvalue = saldodetil_end * ".$obj->COST."
		        WHERE saldo_id LIKE '$saldo_id%' AND heinv_id='".$obj->heinv_id."'";
		$conn->Execute($SQL);



		
		

		$rs->MoveNext();
		
	}
	
        
        
        
        
           unset($obj);
                $obj->message='Calculating Done .. ';
                $data[]=$obj;
        
        
        
        

             
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>