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
		/*$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_branch_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_dateend', '', "{criteria_value}");
		$heinvgro_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvgro_id', '', "{criteria_value}");
		$heinvctg_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvctg_id', '', "{criteria_value}");
		$art_switcher = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_type', '', "{criteria_value}");
		$branch_city   	 = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_area_id', '', "{criteria_value}");
		*/
	}

$data = array();

$delimiter = explode("|",$ids);
$region_id = $delimiter[0];
$branch_id = $delimiter[1];
$startdate = $delimiter[2];
$enddate = $delimiter[3];
$group = $delimiter[4];
$category = $delimiter[5];
$art_switcher = $delimiter[6];
$branch_city = $delimiter[7];

IF ($branch_id!='') 
{
 		$sql= "
			set nocount on
			
		
		    DECLARE @startdate as smalldatetime
			DECLARE @enddate as smalldatetime
		    DECLARE @heinvgro_id as varchar(30)
		    DECLARE @heinvctg_id as varchar(30)
		    DECLARE @art_switcher as varchar (30)
			
			SET @startdate ='$startdate'
			SET @enddate ='$enddate'
		    SET @heinvgro_id ='$heinvgro_id'
		    SET @heinvctg_id='$heinvctg_id'
		    SET @art_switcher= '$art_switcher'
			
			EXEC poshe_RptSalesSummary_bycategory_2 '$region_id','$branch_id','$startdate','$enddate',@heinvgro_id,@heinvctg_id, @art_switcher;
			";
		$rs  = $conn->Execute($sql);
		$totalCount = $rs->recordCount();


//print $totalCount;

 	while (!$rs->EOF) {
		unset($obj);

		$region_id 				= 	trim($rs->fields['region_id']);
		$SQLB					= "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
        $rsB 					= $conn->execute($SQLB);
        $region_name 			= 	trim($rsB->fields['region_name']);
 		$obj->region_id			=	$region_name;

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

		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLA					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsA 					= $conn->execute($SQLA);
        $branch_name 			= 	trim($rsA->fields['branch_name']);
 		$obj->branch_id		=	$branch_name;
			
		$obj->heinvctg_namegroup	=	trim($rs->fields['heinvctg_namegroup']);
		$obj->bondetil_art		=	trim($rs->fields['bondetil_art']);
		$obj->bondetil_mat		=	trim($rs->fields['bondetil_mat']);
		$obj->bondetil_col		=	trim($rs->fields['bondetil_col']);
		$obj->gross				=	trim($rs->fields['gross']);
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->nett				=	trim($rs->fields['nett']);
		$obj->mFP				=	trim($rs->fields['mFP']);
		$obj->mMG				=	trim($rs->fields['mMG']);
		$obj->mDI				=	trim($rs->fields['mDI']);
		
		$data[] = $obj;
 
		$rs->MoveNext();
		}
}
else
{
	IF ($branch_city!='') 
	{
		$sql= "
			set nocount on
			
			DECLARE @startdate as smalldatetime
			DECLARE @enddate as smalldatetime
		    DECLARE @heinvgro_id as varchar(30)
		    DECLARE @heinvctg_id as varchar(30)
		    DECLARE @art_switcher as varchar (30)
			
			SET @startdate ='$startdate'
			SET @enddate ='$enddate'
		    SET @heinvgro_id ='$heinvgro_id'
		    SET @heinvctg_id='$heinvctg_id'
		    SET @art_switcher= '$art_switcher'
			
			EXEC poshe_RptSalesSummary_bycategory_2area '$region_id','$branch_city','$startdate','$enddate',@heinvgro_id,@heinvctg_id, @art_switcher;
			";
		


		$rs  = $conn->Execute($sql);
		$totalCount = $rs->recordCount();


//print $totalCount;

 	while (!$rs->EOF) {
		unset($obj);
		$region_id 				= 	trim($rs->fields['region_id']);
		$SQLB					= "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
        $rsB 					= $conn->execute($SQLB);
        $region_name 			= 	trim($rsB->fields['region_name']);
 		$obj->region_id			=	$region_name;

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

		$obj->branch_city		=	trim($rs->fields['branch_city']);
			
		$obj->heinvctg_namegroup	=	trim($rs->fields['heinvctg_namegroup']);
		$obj->bondetil_art		=	trim($rs->fields['bondetil_art']);
		$obj->bondetil_mat		=	trim($rs->fields['bondetil_mat']);
		$obj->bondetil_col		=	trim($rs->fields['bondetil_col']);
		$obj->gross				=	trim($rs->fields['gross']);
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->nett				=	trim($rs->fields['nett']);
		$obj->mFP				=	trim($rs->fields['mFP']);
		$obj->mMG				=	trim($rs->fields['mMG']);
		$obj->mDI				=	trim($rs->fields['mDI']);
		
		$data[] = $obj;
 
		$rs->MoveNext();
		}

	}
	else
	{
		$sql= "
			set nocount on
			
		
		    DECLARE @startdate as smalldatetime
			DECLARE @enddate as smalldatetime
		    DECLARE @heinvgro_id as varchar(30)
		    DECLARE @heinvctg_id as varchar(30)
		    DECLARE @art_switcher as varchar (30)
			
			SET @startdate ='$startdate'
			SET @enddate ='$enddate'
		    SET @heinvgro_id ='$heinvgro_id'
		    SET @heinvctg_id='$heinvctg_id'
		    SET @art_switcher= '$art_switcher'
			
			EXEC poshe_RptSalesSummary_bycategory_2 '$region_id','$branch_id','$startdate','$enddate',@heinvgro_id,@heinvctg_id, @art_switcher;
			";
		$rs  = $conn->Execute($sql);
		$totalCount = $rs->recordCount();


//print $totalCount;

 	while (!$rs->EOF) {
		unset($obj);

		$region_id 				= 	trim($rs->fields['region_id']);
		$SQLB					= "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
        $rsB 					= $conn->execute($SQLB);
        $region_name 			= 	trim($rsB->fields['region_name']);
 		$obj->region_id			=	$region_name;

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

		$branch_id 				= 	trim($rs->fields['branch_id']);
		$SQLA					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsA 					= $conn->execute($SQLA);
        $branch_name 			= 	trim($rsA->fields['branch_name']);
 		$obj->branch_id		=	$branch_name;
			
		$obj->heinvctg_namegroup	=	trim($rs->fields['heinvctg_namegroup']);
		$obj->bondetil_art		=	trim($rs->fields['bondetil_art']);
		$obj->bondetil_mat		=	trim($rs->fields['bondetil_mat']);
		$obj->bondetil_col		=	trim($rs->fields['bondetil_col']);
		$obj->gross				=	trim($rs->fields['gross']);
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->nett				=	trim($rs->fields['nett']);
		$obj->mFP				=	trim($rs->fields['mFP']);
		$obj->mMG				=	trim($rs->fields['mMG']);
		$obj->mDI				=	trim($rs->fields['mDI']);
		
		$data[] = $obj;
 
		$rs->MoveNext();
		}
	}
}


		
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>
