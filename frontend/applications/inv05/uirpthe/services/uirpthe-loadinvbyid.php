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
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'region_id', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'enddate', '', "{criteria_value}");
			
	}
	

	$data = array();
	
	$sql = "SELECT * FROM master_regionbranch WHERE region_id = '$region_id'  ";
	$rs  = $conn->Execute($sql);
	

	while (!$rs->EOF) {
		$branch_id = $rs->fields['branch_id'];
		
		$SQL = "
					SET NOCOUNT ON;
				
					declare @enddate as smalldatetime;
					declare @heinv_id as varchar(13);
					declare @branch_id as varchar(7);
					
					SET @enddate   	= '$enddate';
					SET @heinv_id 	= '$heinv_id';
					SET @branch_id 	= '$branch_id'		
		
					EXEC inv05he_RptSummaryByInvBranch @enddate, @heinv_id, @branch_id 
				";
		

		$rsI = $conn->Execute($SQL);


		$SQL = "SELECT branch_name FROM master_branch WHERE branch_id='$branch_id'";
		$rsB = $conn->Execute($SQL);
		
	
		unset($obj);
		$obj->branch_id = $branch_id;
		$obj->branch_name = $rsB->fields['branch_name']; 
		$obj->BEG 	= (int) $rsI->fields['BEG']; 
		$obj->RV 	= (int) $rsI->fields['RV']; 
		$obj->TOUT 	= (int) $rsI->fields['TOUT']; 
		$obj->TIN 	= (int) $rsI->fields['TIN']; 
		$obj->TTS 	= (int) $rsI->fields['TTS']; 
		$obj->SL 	= (int) $rsI->fields['SL']; 
		$obj->DO 	= (int) $rsI->fields['DO']; 
		$obj->AJ 	= (int) $rsI->fields['AJ']; 
		$obj->AS 	= (int) $rsI->fields['AS']; 
		$obj->OTHER = (int) $rsI->fields['OTHER']; 
		$obj->END 	= (int) $rsI->fields['END']; 
		$obj->C01 = (int) $rsI->fields['C01'];
		$obj->C02 = (int) $rsI->fields['C02'];
		$obj->C03 = (int) $rsI->fields['C03'];
		$obj->C04 = (int) $rsI->fields['C04'];
		$obj->C05 = (int) $rsI->fields['C05'];
		$obj->C06 = (int) $rsI->fields['C06'];
		$obj->C07 = (int) $rsI->fields['C07'];
		$obj->C08 = (int) $rsI->fields['C08'];
		$obj->C09 = (int) $rsI->fields['C09'];
		$obj->C10 = (int) $rsI->fields['C10'];
		$obj->C11 = (int) $rsI->fields['C11'];
		$obj->C12 = (int) $rsI->fields['C12'];
		$obj->C13 = (int) $rsI->fields['C13'];
		$obj->C14 = (int) $rsI->fields['C14'];
		$obj->C15 = (int) $rsI->fields['C15'];
		$obj->C16 = (int) $rsI->fields['C16'];
		$obj->C17 = (int) $rsI->fields['C17'];
		$obj->C18 = (int) $rsI->fields['C18'];
		$obj->C19 = (int) $rsI->fields['C19'];
		$obj->C20 = (int) $rsI->fields['C20'];
		$obj->C21 = (int) $rsI->fields['C21'];
		$obj->C22 = (int) $rsI->fields['C22'];
		$obj->C23 = (int) $rsI->fields['C23'];
		$obj->C24 = (int) $rsI->fields['C24'];
		$obj->C25 = (int) $rsI->fields['C25'];	
	
	
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