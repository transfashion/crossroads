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
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'branch_id', '', "{criteria_value}");	
		$machine_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'machine_id', '', "{criteria_value}");
		$date = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'date', '', "{criteria_value}");

			
	}
	

	
	
	$data = array();
	unset($obj);
 	$SQL = "

SET NOCOUNT ON
EXEC poshe_TrnBon_ClosingSummary_1 '$region_id','$branch_id','$date','$machine_id'";
 	$rs = $conn->Execute($SQL);
 	
while (!$rs->EOF)
{
 	unset($obj);
 	
	$obj->machine_id = $rs->fields['machine_id'];
	$obj->total_bon = (int)   $rs->fields['total_bon'];
	$obj->total_item = (int)   $rs->fields['total_item'];
	$obj->total_purchased_gross = (float) $rs->fields['total_purchased_gross'];
	$obj->total_purchased_disc = (float) $rs->fields['total_purchased_disc'];
	$obj->total_purchased_nett = (float) $rs->fields['total_purchased_nett'];
	$obj->total_subt = (float) $rs->fields['total_subt'];
	$obj->total_discpaym = (float) $rs->fields['total_discpaym'];
	$obj->total_paymn = (float) $rs->fields['total_paymn'];
	$obj->pospayment_id = $rs->fields['pospayment_id'];
	$obj->pospayment_name = $rs->fields['pospayment_name'];
	$obj->val = (float) $rs->fields['val']; 

	$obj->total_subtredeem = (float) $rs->fields['total_subtredeem'];
	$obj->total_subtdiscadd = (float) $rs->fields['total_subtdiscadd'];
	$obj->total_subtvoucher = (float) (float) $rs->fields['total_subtvoucher'];

 	
 	
 	
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