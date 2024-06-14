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
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		$type = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_type', '', "{criteria_value}");
		$rank = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_rank', '', "{criteria_value}");
		$group = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_group', '', "{criteria_value}");		

	}
	
	 
	$data = array();
 

if ($type=="Fast")
{
			
 	if ($branch_id<>"")
	{
	 	$SQL ="
	 		set nocount on;
			
			DECLARE @branch_id as varchar(7);
			DECLARE @region_id as varchar (10);
			DECLARE @startdate as smalldatetime;
			DECLARE @enddate as smalldatetime;
			DECLARE @rank as varchar(2) ;
			DECLARE @group as varchar (10);
		
			SET @branch_id = '$branch_id';
			SET @region_id = '$region_id';
			SET @startdate = '$startdate';
			SET @enddate = '$enddate';
			SET @rank = '$rank';
			SET @group = '$group';
			
			EXEC inv05_RptFastSlowMv_Fast_byBranch '$branch_id','$region_id','$startdate','$enddate','$rank','$group';
	 		";

	}
	 else 
	{
		$SQL ="
	 		set nocount on;
	
			DECLARE @branch_id as varchar(7);
			DECLARE @region_id as varchar (10);
			DECLARE @startdate as smalldatetime;
			DECLARE @enddate as smalldatetime;
			DECLARE @rank as varchar(2) ;
			DECLARE @group as varchar (10);
		
			SET @branch_id = '$branch_id';
			SET @region_id = '$region_id';
			SET @startdate = '$startdate';
			SET @enddate = '$enddate';
			SET @rank = '$rank';
			SET @group = '$group';
			
			EXEC inv05_RptFastSlowMv_Fast '$branch_id','$region_id','$startdate','$enddate','$rank','$group';
	 		";

	}
}
else  
{
	if ($branch_id<>"")
	{
		$SQL ="
	 		set nocount on;
	
			DECLARE @branch_id as varchar(7);
			DECLARE @region_id as varchar (10);
			DECLARE @startdate as smalldatetime;
			DECLARE @enddate as smalldatetime;
			DECLARE @rank as varchar(2) ;
			DECLARE @group as varchar (10);
		
			SET @branch_id = '$branch_id';
			SET @region_id = '$region_id';
			SET @startdate = '$startdate';
			SET @enddate = '$enddate';
			SET @rank = '$rank';
			SET @group = '$group';
			
			EXEC inv05_RptFastSlowMv_Slow_byBranch '$branch_id','$region_id','$startdate','$enddate','$rank','$group';
			";

			
	 }
	 else
	 {
		$SQL ="
	 		set nocount on;
	
			DECLARE @branch_id as varchar(7);
			DECLARE @region_id as varchar (10);
			DECLARE @startdate as smalldatetime;
			DECLARE @enddate as smalldatetime;
			DECLARE @rank as varchar(2) ;
			DECLARE @group as varchar (10);
			
			SET @branch_id = '$branch_id';	
			SET @region_id = '$region_id';
			SET @startdate = '$startdate';
			SET @enddate = '$enddate';
			SET @rank = '$rank';
			SET @group = '$group';
			
			EXEC inv05_RptFastSlowMv_Slow '$branch_id','$region_id','$startdate','$enddate','$rank','$group';
	 		";
			 			 
			 
	}		
	 
	 
}

		$rs  = $conn->Execute($SQL);	
 
		
	while (!$rs->EOF) {
		unset($obj);
		
 
      	$obj->region_id 		= 	trim($rs->fields['region_id']);
        
		$branch_id 				= 	trim($rs->fields['branch_id']);
        $SQLB					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsB 					= $conn->execute($SQLB);
	
		$branch_name 			= 	trim($rsB->fields['branch_name']);
		$obj->branch_id			=	$branch_name;
		
		
		$heinvgro_id 			= 	trim($rs->fields['heinvgro_id']);
		$SQLC					= "SELECT heinvgro_name FROM master_heinvgro WHERE heinvgro_id = '$heinvgro_id'";
        $rsC 					= $conn->execute($SQLC);
        
        $heinvgro_name 			= 	trim($rsC->fields['heinvgro_name']);
 		$obj->heinvgro_id 		=	$heinvgro_name;

 		
 		$heinvctg_id 			= 	trim($rs->fields['heinvctg_id']);
		$SQLD					= "SELECT heinvctg_name FROM master_heinvctg WHERE heinvctg_id = '$heinvctg_id'";
        $rsD 					= $conn->execute($SQLD);
                                
        $heinvctg_name 			= 	trim($rsD->fields['heinvctg_name']);
 		$obj->heinvctg_id		=	$heinvctg_name;

 		$obj->season_id			=	trim($rs->fields['season_id']);
 		$obj->heinv_id			=	trim($rs->fields['heinv_id']);
		$obj->heinv_art			=	trim($rs->fields['heinv_art']);
		$obj->heinv_mat			=	trim($rs->fields['heinv_mat']);
		$obj->heinv_col			=	trim($rs->fields['heinv_col']);
		$obj->heinv_name		=	trim($rs->fields['heinv_name']);
		$obj->bondetil_qty		=	1*trim($rs->fields['bondetil_qty']);
		
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