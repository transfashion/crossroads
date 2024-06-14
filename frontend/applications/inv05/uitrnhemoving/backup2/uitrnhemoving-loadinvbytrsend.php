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
		
 
		$hemoving_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'hemoving_id', 'hemoving_id', "{criteria_value}");
	}
	

	$data = array();

	$sql = "EXEC [inv05he_RptSummaryByInvBranch_TRSend] '$hemoving_id'";
	$rs  = $conn->Execute($sql);
	

	while (!$rs->EOF) {
	 
		unset($obj);
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