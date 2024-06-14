<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$ids 		= $_POST['ids'];
	$criteria	= $_POST['criteria'];
	$includeconsumable = $_POST['includeconsumable']=='True' ? 1 : 0;
		
	
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		/*
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		*/
			$hemoving_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_id', '', "{criteria_value}");
			
			
	}	



	$sql = "
	SET NOCOUNT ON
	DECLARE @hemoving_id as varchar(50)
	SET @hemoving_id = '$hemoving_id';
	EXEC inv05he_RptTRCheck @hemoving_id";
	
	

//print $sql;
	
	$data = array();
	$rs   = $conn->Execute($sql);
 
	while (!$rs->EOF) {
		unset($obj);
		$obj->REPSEQ				= $rs->fields['REPSEQ'];
		$obj->REPSECTION			= $rs->fields['REPSECTION'];
		$obj->REPSECTIONSEQ			= $rs->fields['REPSECTIONSEQ'];		
		
		$obj->heinv_id	= trim($rs->fields['heinv_id']);
		$obj->AVAIL_C01=trim($rs->fields['AVAIL_C01']);
$obj->AVAIL_C02=trim($rs->fields['AVAIL_C02']);
$obj->AVAIL_C03=trim($rs->fields['AVAIL_C03']);
$obj->AVAIL_C04=trim($rs->fields['AVAIL_C04']);
$obj->AVAIL_C05=trim($rs->fields['AVAIL_C05']);
$obj->AVAIL_C06=trim($rs->fields['AVAIL_C06']);
$obj->AVAIL_C07=trim($rs->fields['AVAIL_C07']);
$obj->AVAIL_C08=trim($rs->fields['AVAIL_C08']);
$obj->AVAIL_C09=trim($rs->fields['AVAIL_C09']);
$obj->AVAIL_C10=trim($rs->fields['AVAIL_C10']);
$obj->AVAIL_C11=trim($rs->fields['AVAIL_C11']);
$obj->AVAIL_C12=trim($rs->fields['AVAIL_C12']);
$obj->AVAIL_C13=trim($rs->fields['AVAIL_C13']);
$obj->AVAIL_C14=trim($rs->fields['AVAIL_C14']);
$obj->AVAIL_C15=trim($rs->fields['AVAIL_C15']);
$obj->AVAIL_C16=trim($rs->fields['AVAIL_C16']);
$obj->AVAIL_C17=trim($rs->fields['AVAIL_C17']);
$obj->AVAIL_C18=trim($rs->fields['AVAIL_C18']);
$obj->AVAIL_C19=trim($rs->fields['AVAIL_C19']);
$obj->AVAIL_C20=trim($rs->fields['AVAIL_C20']);
$obj->AVAIL_C21=trim($rs->fields['AVAIL_C21']);
$obj->AVAIL_C22=trim($rs->fields['AVAIL_C22']);
$obj->AVAIL_C23=trim($rs->fields['AVAIL_C23']);
$obj->AVAIL_C24=trim($rs->fields['AVAIL_C24']);
$obj->AVAIL_C25=trim($rs->fields['AVAIL_C25']);

	$obj->C01=trim($rs->fields['C01']);
$obj->C02=trim($rs->fields['C02']);
$obj->C03=trim($rs->fields['C03']);
$obj->C04=trim($rs->fields['C04']);
$obj->C05=trim($rs->fields['C05']);
$obj->C06=trim($rs->fields['C06']);
$obj->C07=trim($rs->fields['C07']);
$obj->C08=trim($rs->fields['C08']);
$obj->C09=trim($rs->fields['C09']);
$obj->C10=trim($rs->fields['C10']);
$obj->C11=trim($rs->fields['C11']);
$obj->C12=trim($rs->fields['C12']);
$obj->C13=trim($rs->fields['C13']);
$obj->C14=trim($rs->fields['C14']);
$obj->C15=trim($rs->fields['C15']);
$obj->C16=trim($rs->fields['C16']);
$obj->C17=trim($rs->fields['C17']);
$obj->C18=trim($rs->fields['C18']);
$obj->C19=trim($rs->fields['C19']);
$obj->C20=trim($rs->fields['C20']);
$obj->C21=trim($rs->fields['C21']);
$obj->C22=trim($rs->fields['C22']);
$obj->C23=trim($rs->fields['C23']);
$obj->C24=trim($rs->fields['C24']);
$obj->C25=trim($rs->fields['C25']);

		
		$obj->heinv_art = $rs->fields['heinv_art'];
		$obj->heinv_mat = $rs->fields['heinv_mat'];
		$obj->heinv_col = $rs->fields['heinv_col'];
		$obj->heinv_name= $rs->fields['heinv_name'];



		$data[] = $obj;

		$rs->MoveNext();
	}
	





	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = count($data);
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>