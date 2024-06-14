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
		
			
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		
	

	}
	
	 
	$data = array();

 
		$sql = "
		 SET NOCOUNT ON

		DECLARE @startdate as smalldatetime;
		DECLARE @enddate as smalldatetime;
		
		SET @startdate = '$startdate';
		SET @enddate = '$enddate';

		EXEC poshe_RptSalesSummary_BySize  @startdate, @enddate,'$region_id','$branch_id' ";
		$rs  = $conn->Execute($sql);
	
	 
 
		
	while (!$rs->EOF) {
		unset($obj);
		$obj->REP_SEQ				=	trim($rs->fields['REP_SEQ']);
		$obj->REP_SECTION			=	trim($rs->fields['REP_SECTION']);
		$obj->REP_SECTION_SEQ		=	(int)trim($rs->fields['REP_SECTION_SEQ']);
		$obj->heinvgro_id			=   trim($rs->fields['heinvgro_id']);
		$obj->heinvgro_name			=   trim($rs->fields['heinvgro_name']);
		$obj->heinvctg_id			=   trim($rs->fields['heinvctg_id']);
		$obj->heinvctg_name			=   trim($rs->fields['heinvctg_name']);
		$obj->SL					=   (int) trim($rs->fields['SL']);
		$obj->C01					=   trim($rs->fields['C01']);
		$obj->C02					=   trim($rs->fields['C02']);
		$obj->C03					=   trim($rs->fields['C03']);
		$obj->C04					=   trim($rs->fields['C04']);
		$obj->C05					=   trim($rs->fields['C05']);
		$obj->C06					=   trim($rs->fields['C06']);	
		$obj->C07					=   trim($rs->fields['C07']);
		$obj->C08					=   trim($rs->fields['C08']);	
		$obj->C09					=   trim($rs->fields['C09']);
		$obj->C10					=   trim($rs->fields['C10']);
		$obj->C11					=   trim($rs->fields['C11']);
		$obj->C12					=   trim($rs->fields['C12']);
		$obj->C13					=   trim($rs->fields['C13']);
		$obj->C14					=   trim($rs->fields['C14']);
		$obj->C15					=   trim($rs->fields['C15']);
		$obj->C16					=   trim($rs->fields['C16']);
		$obj->C17					=   trim($rs->fields['C17']);
		$obj->C18					=   trim($rs->fields['C18']);
		$obj->C19					=   trim($rs->fields['C19']);
		$obj->C20					=   trim($rs->fields['C20']);
		$obj->C21					=   trim($rs->fields['C21']);
		$obj->C22					=   trim($rs->fields['C22']);
		$obj->C23					=   trim($rs->fields['C23']);
		$obj->C24					=   trim($rs->fields['C24']);
		$obj->C25					=   trim($rs->fields['C25']);
		
		
	 
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