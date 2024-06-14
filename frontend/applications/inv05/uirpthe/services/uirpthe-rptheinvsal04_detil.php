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
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
		
	}	



	$sql = "
	
			set nocount on;
	
			DECLARE @region_id as varchar(5);
			DECLARE @datestart as smalldatetime;
			DECLARE @dateend as smalldatetime;
			
			SET @region_id = '$region_id';
			SET @datestart = '$datestart';
			SET @dateend = '$dateend';

			EXEC poshe_RptSalesSummary_bycategory @region_id, @datestart,@dateend;
	";


//	print $sql;
	


	
	$data = array();
	$rs   = $conn->Execute($sql);
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->branch_id				= $rs->fields['branch_id'];
		$obj->branch_name			= $rs->fields['branch_name'];
		
		$obj->heinvgro_id			= $rs->fields['heinvgro_id'];
		$obj->heinvgro_name			= $rs->fields['heinvgro_name'];
		$obj->heinvctg_id			= $rs->fields['heinvctg_id'];
		$obj->heinvctg_name			= $rs->fields['heinvctg_name'];
		$obj->heinvctg_namegroup	= $rs->fields['heinvctg_namegroup'];
		
		$obj->qty	= (float) $rs->fields['qty'];
		$obj->gross	= (float) $rs->fields['gross'];
		$obj->nett	= (float) $rs->fields['nett'];
		$obj->mFP	= (float) $rs->fields['mFP'];
		$obj->mMG	= (float) $rs->fields['mMG'];
		$obj->mDI	= (float) $rs->fields['mDI'];
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