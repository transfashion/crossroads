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
	    $branch_id   = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'branch_id', '', "{criteria_value}");
        $cacheid   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'cacheid', '', "{criteria_value}");
        $_year   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'year', '', "{criteria_value}");
        $heinvclosingstatus_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'heinvclosingstatus_id', '', "{criteria_value}");
  		
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
        
        
        
        
        
        
        
 		$sql = "SELECT * from dbo.cache_heinvsummary where cacheid='$cacheid' AND branch_id='$branch_id'";
		$rs = $conn->Execute($sql);
        
        
        while (!$rs->EOF) {
            	$linenum++;
            	unset($obj);
			$obj->saldo_id = $_year.str_pad($_month, 2, "0", STR_PAD_LEFT).str_pad($_day, 2, "0", STR_PAD_LEFT)."-".$region_id."-".$branch_id;
			$obj->saldodetil_line = $linenum;
			$obj->saldodetil_begin = $rs->fields['BEG'];
			$obj->saldodetil_recv = $rs->fields['RV'];
			$obj->saldodetil_trou = $rs->fields['TOUT'];
			$obj->saldodetil_trin = $rs->fields['TIN'];
			$obj->saldodetil_trtran = $rs->fields['TTS'];
			$obj->saldodetil_sales = $rs->fields['SL'];
			$obj->saldodetil_do = $rs->fields['DO'];
			$obj->saldodetil_adj = $rs->fields['AJ'];
			$obj->saldodetil_as = $rs->fields['AS'];
			$obj->saldodetil_oth = $rs->fields['OTHER'];
			$obj->saldodetil_end = $rs->fields['END'];
			$obj->heinv_id = $rs->fields['heinv_id'];
			$obj->C01 = $rs->fields['C01'];
			$obj->C02 = $rs->fields['C02'];
			$obj->C03 = $rs->fields['C03'];
			$obj->C04 = $rs->fields['C04'];
			$obj->C05 = $rs->fields['C05'];
			$obj->C06 = $rs->fields['C06'];
			$obj->C07 = $rs->fields['C07'];
			$obj->C08 = $rs->fields['C08'];
			$obj->C09 = $rs->fields['C09'];
			$obj->C10 = $rs->fields['C10'];
			$obj->C11 = $rs->fields['C11'];
			$obj->C12 = $rs->fields['C12'];
			$obj->C13 = $rs->fields['C13'];
			$obj->C14 = $rs->fields['C14'];
			$obj->C15 = $rs->fields['C15'];
			$obj->C16 = $rs->fields['C16'];
			$obj->C17 = $rs->fields['C17'];
			$obj->C18 = $rs->fields['C18'];
			$obj->C19 = $rs->fields['C19'];
			$obj->C20 = $rs->fields['C20'];
			$obj->C21 = $rs->fields['C21'];
			$obj->C22 = $rs->fields['C22'];
			$obj->C23 = $rs->fields['C23'];
			$obj->C24 = $rs->fields['C24'];
			$obj->C25 = $rs->fields['C25'];
			
			
        			try {
        				$SQL = SQLUTIL::SQL_InsertFromObject('transaksi_hesaldodetil', $obj);			
        				$conn->Execute($SQL);
        			} catch (exception $e) {
        				print "Error.\n";
        				throw new Exception($e->GetMessage());	
        			}
		    	$rs->MoveNext();
            }
            
            
    unset($obj);
    $obj->success=1;
    $data[]=$obj;
        
        
        
        
        
        
        
        
        
        

             
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>