<?
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
		
		$heinv_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'heinv_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'branch_id', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'enddate', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'startdate', '', "{criteria_value}");
			
	}
	


	

	$SQL = "
	
				SET NOCOUNT ON;
	
				declare @date_start as smalldatetime;
				declare @date_end as smalldatetime;
				declare @heinv_id as varchar(13);
				declare @branch_id as varchar(7);
				
				SET @date_start = '$startdate';
				SET @date_end   = '$enddate';
				SET @heinv_id = '$heinv_id';
				SET @branch_id = '$branch_id'
				
				EXEC inv05he_RptSummaryByInvBranchHistory
													@date_start,
													@date_end,
													@heinv_id,
													@branch_id	
	";	

	$rs = $conn->Execute($SQL);
	
	
	$data = array();	
	while (!$rs->EOF) {
	
		unset($obj);
		
		$obj->id		= $rs->fields['id'];
		$obj->date		= SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['date']));
		$obj->line		= (int) $rs->fields['line'];
		$obj->QTY		= (int) $rs->fields['QTY'];
		
		$obj->C01 		= (int) $rs->fields['C01'];
		$obj->C02 		= (int) $rs->fields['C02'];
		$obj->C03		= (int) $rs->fields['C03'];
		$obj->C04 		= (int) $rs->fields['C04'];
		$obj->C05 		= (int) $rs->fields['C05'];
		$obj->C06 		= (int) $rs->fields['C06'];
		$obj->C07 		= (int) $rs->fields['C07'];
		$obj->C08 		= (int) $rs->fields['C08'];
		$obj->C09 		= (int) $rs->fields['C09'];
		$obj->C10		= (int) $rs->fields['C10'];
		$obj->C11 		= (int) $rs->fields['C11'];
		$obj->C12 		= (int) $rs->fields['C12'];
		$obj->C13 		= (int) $rs->fields['C13'];
		$obj->C14 		= (int) $rs->fields['C14'];
		$obj->C15 		= (int) $rs->fields['C15'];
		$obj->C16 		= (int) $rs->fields['C16'];
		$obj->C17 		= (int) $rs->fields['C17'];
		$obj->C18 		= (int) $rs->fields['C18'];
		$obj->C19 		= (int) $rs->fields['C19'];
		$obj->C20 		= (int) $rs->fields['C20'];
		$obj->C21 		= (int) $rs->fields['C21'];
		$obj->C22 		= (int) $rs->fields['C22'];
		$obj->C23 		= (int) $rs->fields['C23'];
		$obj->C24 		= (int) $rs->fields['C24'];
		$obj->C25 		= (int) $rs->fields['C25'];				
	
	
		$data[] = $obj;
		$rs->MoveNext();
	}

	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>