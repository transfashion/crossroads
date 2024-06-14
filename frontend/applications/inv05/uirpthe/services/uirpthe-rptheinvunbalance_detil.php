<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];
	$ids 		= $_POST['ids'];


	$param = "";
	$SQL_CRITERIA = "";
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		
		$region_id	= SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$startdate 	= SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate  	= SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
		$type 		= SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_type', '', "{criteria_value}");
	}
	
 
	$data = array();

	$SQL = "
		 
		SET NOCOUNT ON
		DECLARE @region_id as nvarchar(5);
		DECLARE @startdate as smalldatetime;
		DECLARE @enddate as smalldatetime;
		DECLARE @type as nvarchar (30);
		
		SET @region_id = '$region_id'
		SET @startdate = '$startdate'
		SET @enddate   = '$enddate'
		SET @type   = '$type'
		
		EXEC inv05_RptUnbalance @region_id,@startdate,@enddate,@type;
		
		";
// EXEC inv05_RptUnbalance '$region_id',@startdate,@enddate,@type

	$rs = $conn->Execute($SQL);

  


	
	while (!$rs->EOF) {
		unset($obj);
		$obj->hemoving_id		=	trim($rs->fields['hemoving_id']);
		$obj->hemoving_date		=	trim($rs->fields['hemoving_date']);
		$obj->hemoving_date_fr	=	trim($rs->fields['hemoving_date_fr']);
		$obj->hemoving_date_to	=	trim($rs->fields['hemoving_date_to']);
		$obj->time_diff			=	trim($rs->fields['time_diff']);
		$obj->diffmo			=	trim($rs->fields['diffmo']);
		$obj->branch_id_fr		=	trim($rs->fields['branch_id_fr']);
		$obj->branch_name_from	=	trim($rs->fields['branch_name_from']);
		$obj->branch_id_to		=	trim($rs->fields['branch_id_to']); 
 		$obj->branch_name_to 	=	trim($rs->fields['branch_name_to']); 
 		$obj->QTY_PROP			=	trim($rs->fields['QTY_PROP']); 
 		$obj->QTY_SEND			=	trim($rs->fields['QTY_SEND']); 
 		$obj->QTY_RECV			=	trim($rs->fields['QTY_RECV']); 
 		 
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