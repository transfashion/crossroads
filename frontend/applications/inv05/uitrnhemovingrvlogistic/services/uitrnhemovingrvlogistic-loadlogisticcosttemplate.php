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
		
		$template = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'templatecode', '', "{criteria_value}");
			
	}
	

	$data = array();
	
	$sql = "SELECT * FROM master_acclogistictmp WHERE acclogisticcosttmp_type = '$template' ORDER BY acclogisticcosttmp_order ";
	$rs  = $conn->Execute($sql);
	

	while (!$rs->EOF) {
		unset($obj);
		$obj->hemovinglogisticcost_descr = $rs->fields['acclogisticcosttmp_descr'];
		$obj->hemovinglogisticcost_amount = 0;
		$obj->hemovinglogisticcost_rate = 0;
		$obj->acclogisticcost_id = $rs->fields['acclogisticcostacc_id'];
		$obj->acclogisticcosttmp_code = $rs->fields['acclogisticcosttmp_code'];
		
		$sql = "SELECT * FROM master_acclogisticcost WHERE acclogisticcost_id='".$obj->acclogisticcost_id."' ";
		$rsI = $conn->Execute($sql);
		$obj->isusingrekanan = $rsI->fields['acclogisticcost_isusingrekanan'];
		$obj->ismulticurrency = $rsI->fields['acclogisticcost_ismulticurrency'];
		$obj->iscostcomponent = $rsI->fields['acclogisticcost_iscostcomponent'];

		$obj->rekanan_id = "0";
		$obj->currency_id = "IDR";

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