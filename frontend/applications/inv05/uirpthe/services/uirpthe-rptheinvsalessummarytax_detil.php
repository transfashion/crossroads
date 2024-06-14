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
		
		$bon_region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_location_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'branch_id', '', "{criteria_value}");
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");		
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

		EXEC poshe_RptSalesSummary_tax '$bon_region_id','$region_id','$branch_id',@startdate, @enddate ";
		$rs  = $conn->Execute($sql);
	
	 
		
		
	while (!$rs->EOF) {
		unset($obj);
		$obj->bon_id			=	trim($rs->fields['bon_id']);
		$obj->bon_date			=	trim($rs->fields['bon_date']);
		$obj->region_name		=	trim($rs->fields['region_name']);

		$obj->bon_itemqty		=	(float) trim($rs->fields['bon_itemqty']);
		$obj->bon_msalegross	=	(float) trim($rs->fields['bon_msalegross']);
		$obj->bon_msaletax		=	(float) trim($rs->fields['bon_msaletax']);
		$obj->bon_msalenet		=	(float) trim($rs->fields['bon_msalenet']);
		$obj->itemgrossori		=	(float) trim($rs->fields['itemgrossori']);
		$obj->nett				=	(float) trim($rs->fields['nett']);
		$obj->disc				=	(float) trim($rs->fields['disc']);
		$obj->tgl				=	 trim($rs->fields['tgl']);
		
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