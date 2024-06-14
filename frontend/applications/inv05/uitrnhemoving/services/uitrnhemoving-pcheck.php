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
 
 		$hemoving_id =  SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'hemoving_id', 'hemoving_id', "{criteria_value}");		
		$event = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'event', 'event', "{criteria_value}");
	}
	


	$sql = "SELECT * FROM transaksi_hemoving WHERE hemoving_id = '$hemoving_id' ";
	$rs  = $conn->Execute($sql);
	$region_id = $rs->fields['region_id'];
	
	/* cek apakah region ini didisable cek qty nya */
	$sql = "SELECT * FROM master_region WHERE region_id = '$region_id'";
	$rs  = $conn->Execute($sql);
	
	$region_ppropchk_isdisabled = $rs->fields['region_ppropchk_isdisabled'];
	$region_psendchk_isdisabled = $rs->fields['region_psendchk_isdisabled'];
	

	$pcheck_isdisabled = 0;
	switch ($event) {
		case "PROP" :
			$pcheck_isdisabled = $region_ppropchk_isdisabled;
			break;
			
		case "SEND" :
			$pcheck_isdisabled = $region_psendchk_isdisabled;
			break;	
		
		default:
			$pcheck_isdisabled = 0;
	}


	$data = array();
	$obj->pcheck_isdisabled = (int) $pcheck_isdisabled;
	$data[] = $obj;

	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>