<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];
	$ids		= $_POST['ids'];
	
	$param = "";
	$SQL_CRITERIA = "";
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
 		$branch_city = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_area', '', "{criteria_value}");
 		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
 		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		*/
	}

$data = array();

$delimiter = explode("|",$ids);
$region_id = $delimiter[0];
$branch_id = $delimiter[1];
$branch_city = $delimiter[4];
$startdate = $delimiter[2];
$enddate = $delimiter[3];
//print $enddate; 
if ($branch_id<>"")
{
	 $sql = "
		SET NOCOUNT ON

		DECLARE @region_id as varchar(5);
		DECLARE @branch_id as varchar (7);
		DECLARE @branch_city as varchar (20);  
		DECLARE @startdate as smalldatetime
		DECLARE @enddate as smalldatetime
		
		SET @region_id = '$region_id'
		SET @branch_id = '$branch_id'
		SET @branch_city = '$branch_city'
		SET @startdate ='$startdate'
		SET @enddate ='$enddate'
		
		EXEC  poshe_RptSales_ByROw '$region_id','$branch_id','$startdate','$enddate','$branch_city'";
}
else
{
 	if ($branch_city<>"")
 	{
	 $sql = "
		SET NOCOUNT ON

		DECLARE @region_id as varchar(5);
		DECLARE @branch_id as varchar (7);
		DECLARE @branch_city as varchar (20);  
		DECLARE @startdate as smalldatetime
		DECLARE @enddate as smalldatetime
		
		SET @region_id = '$region_id'
		SET @branch_id = '$branch_id'
		SET @branch_city = '$branch_city'
		SET @startdate ='$startdate'
		SET @enddate ='$enddate'
		
		EXEC  poshe_RptSales_ByROw '$region_id','$branch_id','$startdate','$enddate','$branch_city'";
	}		
	else
	{
	$sql = "	
		SET NOCOUNT ON
		
		declare @startdate as smalldatetime
		declare @enddate as smalldatetime
		set @startdate ='$startdate'
		set @enddate ='$enddate'

		EXEC  poshe_RptSales_ByROw_All '$region_id','$startdate','$enddate'";
	}
}	
		$rs  = $conn->Execute($sql);

	while (!$rs->EOF) {
		unset($obj);
		$obj->region_id			=	trim($rs->fields['region_id']);
		
		$branch_id				=   trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		$obj->bon_id			=	trim($rs->fields['bon_id']);
		$obj->bon_date			=	trim($rs->fields['bon_date']);
		$obj->bon_createby		=	trim($rs->fields['bon_createby']);
		$obj->bondetil_line		=	1*trim($rs->fields['bondetil_line']);
		$obj->heinv_id			=	trim($rs->fields['heinv_id']);
		$obj->heinv_art			=	trim($rs->fields['heinv_art']);
		$obj->heinv_mat			=	trim($rs->fields['heinv_mat']);
		$obj->heinv_col			=	trim($rs->fields['heinv_col']);
		$obj->season_id			=	trim($rs->fields['season_id']);
		$obj->heinv_name		=	trim($rs->fields['heinv_name']);
		$obj->bondetil_qty		=	(float) trim($rs->fields['bondetil_qty']);
		$obj->bondetil_vou01id	=   trim($rs->fields['bondetil_vou01id']);
        $obj->voucher01_name	=   trim($rs->fields['voucher01_name']);
        $obj->bondetil_mdiscpvou01	= (float)  trim($rs->fields['bondetil_mdiscpvou01']);
        $obj->bondetil_mdiscrvou01	= (float)  trim($rs->fields['bondetil_mdiscrvou01']);
        $obj->itemgrossori		=	(float) trim($rs->fields['itemgrossori']);
		$obj->itemgross			=	(float) trim($rs->fields['itemgross']);
		$obj->itemdisc			=	(float) trim($rs->fields['itemdisc']);
		$obj->itemnett			=	(float) trim($rs->fields['itemnett']);
		$obj->paymdisc			=	(float) trim($rs->fields['paymdisc']);
		$obj->nett				=	(float) trim($rs->fields['nett']);
		$obj->heinvgro_id		=	trim($rs->fields['heinvgro_id']); 
		$obj->heinvgro_name		=	trim($rs->fields['heinvgro_name']); 
		$obj->heinvctg_id		=	trim($rs->fields['heinvctg_id']); 
		$obj->heinvctg_name		=	trim($rs->fields['heinvctg_name']); 
		$obj->bondetil_size		=	trim($rs->fields['bondetil_size']); 
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