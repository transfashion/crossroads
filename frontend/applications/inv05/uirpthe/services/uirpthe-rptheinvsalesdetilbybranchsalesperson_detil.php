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
		
		$bon_region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_location_region_id', '', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");

		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_location_branch_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		
		

	}
	
	$delimiter = explode("|",$ids);

	$data = array();

	$tgl =   $delimiter[5];


		$sql = "
		 SET NOCOUNT ON

		DECLARE @startdate as smalldatetime;
		DECLARE @enddate as smalldatetime;
		DECLARE @day as int
		SET @startdate = '$startdate';
		SET @enddate = '$enddate';
		SET @day = '$tgl';

		EXEC poshe_RptSalesSummary_4 '$region_id','$bon_region_id','$branch_id',@startdate, @enddate,@day";
		$rs  = $conn->Execute($sql);
	
 
		
	while (!$rs->EOF) {
		unset($obj);
		//$obj->branch_id			=	trim($rs->fields['branch_id']);
		//$obj->branch_name		=	trim($rs->fields['branch_name']);
		$obj->bon_id			=	trim($rs->fields['bon_id']);
		//$obj->bon_date			=	trim($rs->fields['bon_date']);
		//$obj->bon_createby		=	trim($rs->fields['heinv_id']);
		//$obj->bondetil_line		=	(int) trim($rs->fields['bondetil_line']);
		
		//DIPATCH SEPERTI INI
		$obj->heinv_id			=	trim($rs->fields['bon_date']);
		
		
		
		//$obj->bondetil_descr	=	trim($rs->fields['bondetil_descr']);
		//$obj->bondetil_art		=	trim($rs->fields['bondetil_art']);
		//$obj->bondetil_mat		=	trim($rs->fields['bondetil_mat']);
		//$obj->bondetil_col		=	trim($rs->fields['bondetil_col']);
		//$obj->bondetil_size		=	trim($rs->fields['bondetil_size']);
		//$obj->region_id			=	trim($rs->fields['region_id']);
		$obj->bondetil_qty		=	(int) trim($rs->fields['bondetil_qty']);
		$obj->salesperson_name	=	trim($rs->fields['salesperson_name']);
		$obj->itemgrossori		=	(float) trim($rs->fields['itemgrossori']);
		$obj->itemgross			=	(float) trim($rs->fields['itemgross']);
		$obj->itemnett			=	(float) trim($rs->fields['itemnett']);
		$obj->nett				=	(float) trim($rs->fields['nett']);
		$obj->nett_full			=	(float) trim($rs->fields['nett_full']);
		$obj->nett_disc			=	(float) trim($rs->fields['nett_disc']);

		$obj->ID_group1			=	trim($rs->fields['branch_name']);
		$obj->ID_group2			=	trim($rs->fields['salesperson_name']);
		$obj->ID_detil			=	trim($rs->fields['bon_id']);		


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