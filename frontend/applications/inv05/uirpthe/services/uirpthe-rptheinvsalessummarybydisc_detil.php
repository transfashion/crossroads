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
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		$branch_city = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_area', '', "{criteria_value}");
		$type = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_type', '', "{criteria_value}");
*/
	}
$delimiter = explode ("|",$ids);
$region_id	= $delimiter[0];
$branch_id	= $delimiter[1];
$startdate	= $delimiter[2];
$enddate	= $delimiter[3];
$branch_city = $delimiter[4];
$type 		= $delimiter[5];

	$data = array();
IF ($type=='Format #1')
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


		EXEC poshe_RptSalesSummary_ByDisc '$region_id','$branch_id','$startdate','$enddate','$branch_city'";
}
else
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


		EXEC poshe_RptSalesSummary_ByDisc#2 '$region_id','$branch_id','$startdate','$enddate','$branch_city'";
}		
		
		$rs  = $conn->Execute($sql);

IF ($type=='Format #2')
{
	while (!$rs->EOF) {
		unset($obj);
		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLA					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsA 					= $conn->execute($SQLA);
        $branch_name 			= 	trim($rsA->fields['branch_name']);
 		$obj->branch_id			=	$branch_name;
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		$obj->discprcgroup		=	trim($rs->fields['discprcgroup']);
		$obj->tipe				=	'GROSS';
		$obj->detil				=	(float) trim($rs->fields['gross']); 
 
		$data[] = $obj;	
		
		unset($obj);
		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLB					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsB 					= $conn->execute($SQLB);
        $branch_name 			= 	trim($rsB->fields['branch_name']);
 		$obj->branch_id		=	$branch_name;
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		$obj->discprcgroup		=	trim($rs->fields['discprcgroup']);
		$obj->tipe				=	'NETT';
		$obj->detil				=	(float) trim($rs->fields['nett']); 
 
		$data[] = $obj;					
					
					
		unset($obj);
		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLC					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsC					= $conn->execute($SQLC);
        $branch_name 			= 	trim($rsC->fields['branch_name']);
 		$obj->branch_id		=	$branch_name;
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		$obj->discprcgroup		=	trim($rs->fields['discprcgroup']);
		$obj->tipe				=	'QTY';
		$obj->detil				=	(float) trim($rs->fields['qty']);  
 

		$data[] = $obj;
		
		
 
		$rs->MoveNext();
	}
	
}
else
{
		while (!$rs->EOF) {
		unset($obj);
		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLA					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsA 					= $conn->execute($SQLA);
        $branch_name 			= 	trim($rsA->fields['branch_name']);
 		$obj->branch_id		=	$branch_name;
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		$obj->heinvgro_name		=	trim($rs->fields['heinvgro_name']);
		$obj->heinvctg_name		=	trim($rs->fields['heinvctg_name']);
		$obj->discprcgroup		=	trim($rs->fields['discprcgroup']);
		$obj->tipe				=	'GROSS';
		$obj->detil				=	(float) trim($rs->fields['gross']); 
 
		$data[] = $obj;		
		
		
		unset($obj);
		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLB					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsB 					= $conn->execute($SQLB);
        $branch_name 			= 	trim($rsB->fields['branch_name']);
 		$obj->branch_id		=	$branch_name;
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		$obj->heinvgro_name		=	trim($rs->fields['heinvgro_name']);
		$obj->heinvctg_name		=	trim($rs->fields['heinvctg_name']);
		$obj->discprcgroup		=	trim($rs->fields['discprcgroup']);
		$obj->tipe				=	'NETT';
		$obj->detil				=	(float) trim($rs->fields['nett']); 
 
		$data[] = $obj;					
					
					
		unset($obj);
		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLC					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsC					= $conn->execute($SQLC);
        $branch_name 			= 	trim($rsC->fields['branch_name']);
 		$obj->branch_id		=	$branch_name;
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		$obj->heinvgro_name		=	trim($rs->fields['heinvgro_name']);
		$obj->heinvctg_name		=	trim($rs->fields['heinvctg_name']);
		$obj->discprcgroup		=	trim($rs->fields['discprcgroup']);
		$obj->tipe				=	'QTY';
		$obj->detil				=	(float) trim($rs->fields['qty']);  
 

		$data[] = $obj;
		
 
		$rs->MoveNext();
	}
}
	 	


	//	$data[] = $obj;
		
			
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>