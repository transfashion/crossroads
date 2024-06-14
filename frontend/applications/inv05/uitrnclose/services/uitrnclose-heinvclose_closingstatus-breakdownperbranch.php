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
        
          

	/* Mulai Generate Data */
	$date_start = "$_year-$_month-1"; 
	$date_end   = "$_year-$_month-$_day"; 
	

	$sql = "
		
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

			EXEC inv05he_RptSummaryByBranch @date_start, @date_end, @region_id, @branch_id, @FLATMODE, @CACHEID, 1
		";
        $conn->execute($sql);
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