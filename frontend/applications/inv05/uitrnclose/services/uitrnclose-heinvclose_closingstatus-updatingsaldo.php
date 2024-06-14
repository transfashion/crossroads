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
        
            
       $__saldo_id =  $_year.str_pad($_month, 2, "0", STR_PAD_LEFT).str_pad($_day, 2, "0", STR_PAD_LEFT)."-".$region_id."-".$branch_id;
		$__criteria = "saldo_id='$__saldo_id'";
            
            				 
		unset($obj);
		$obj->saldo_isdisabled=0;
		$SQL = SQLUTIL::SQL_UpdateFromObject('transaksi_hesaldo', $obj, $__criteria);
		$conn->Execute($SQL);
            
            
             
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>