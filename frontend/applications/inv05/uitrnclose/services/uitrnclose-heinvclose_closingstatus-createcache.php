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
	
        $heinvclosingstatus_id   = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'heinvclosingstatus_id', '', "{criteria_value}"); 		
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
        
          

	/* Mulai Generate Data */
	$sql = "SELECT id=cast(newid() as varchar(50)) ";
	$rs  = $conn->Execute($sql);
	$cacheid = $rs->fields['id'];
	$date_start = "$_year-$_month-1"; 
	$date_end   = "$_year-$_month-$_day"; 
	

	$sql = "DELETE FROM cache_heinvsummary WHERE cacheid='$cacheid'";
	$conn->Execute($sql);
    unset($obj);
    $obj->success=1;
    $obj->cacheid=$cacheid;
    $data[]=$obj;
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>