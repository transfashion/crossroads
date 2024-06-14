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
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart_new = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart_new', '', "{criteria_value}");
		$dateend_new   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend_new',   '', "{criteria_value}");
		$datestart_old = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart_old', '', "{criteria_value}");
		$dateend_old   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend_old',   '', "{criteria_value}");
		
	}	



	$sql = "
	SET NOCOUNT ON
	DECLARE @region_id as varchar(5)
	DECLARE @startdate_now as smalldatetime
	DECLARE @enddate_now as smalldatetime
	DECLARE @startdate_old as smalldatetime
	DECLARE @enddate_old as smalldatetime


	SET @region_id ='$region_id'
	SET @startdate_now ='$datestart_new'
	SET @enddate_now ='$dateend_new'
	SET @startdate_old ='$datestart_old'
	SET @enddate_old ='$dateend_old'

	EXEC poshe_RptSalesSummary_ByRegion_2 @region_id,@startdate_now,@enddate_now,@startdate_old,@enddate_old 	";


 	//print $sql;
	
	
	
	$data = array();
	$rs   = $conn->Execute($sql);
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->branch_id				= $rs->fields['branch_id'];
		$obj->branch_name			= $rs->fields['branch_name'];
		
		
		$obj->qty_2		= (float) $rs->fields['qty_2'];
		$obj->gross_2	= (float) $rs->fields['gross_2'];
		$obj->nett_2	= (float) $rs->fields['nett_2'];
		
		$obj->qty_1		= (float) $rs->fields['qty_1'];
		$obj->gross_1	= (float) $rs->fields['gross_1'];
		$obj->nett_1	= (float) $rs->fields['nett_1'];
		
 
		$obj->qty_total		= (float) $rs->fields['qty_total'];
		$obj->gross_total	= (float) $rs->fields['gross_total'];
		$obj->nett_total	= (float) $rs->fields['nett_total'];
 
		
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