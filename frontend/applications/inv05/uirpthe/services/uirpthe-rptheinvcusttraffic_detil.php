<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];
	$ids 		= $_POST['ids'];
	$tgl 		= $_POST['day'];


set_time_limit( 6000);


	$param = "";
	$SQL_CRITERIA = "";
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		$type = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_type', '', "{criteria_value}");
		$date = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_date', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");		
		
	}
	
 
	$data = array();

 
 if ($type=="Daily")
 {	
	if ($branch_id<>"")
	{
	 	$sql = "
		 	SET NOCOUNT ON
	
			DECLARE @date as smalldatetime ;
			DECLARE @region_id as varchar(5);
			DECLARE @branch_id  as varchar(7);
							
			SET @date 		= '$date'
			SET @region_id 	= '$region_id'
			SET @branch_id 	= '$branch_id'
		
			

			
			EXEC inv05_RptCustTrafficByBranch @date,@region_id,@branch_id";
	}		
	else
	{
		$sql = "
		 	SET NOCOUNT ON
	
			DECLARE @date as smalldatetime ;
			DECLARE @region_id as varchar(5);
			
			SET @date 		= '$date'
			SET @region_id 	= '$region_id'
					
			EXEC inv05_RptCustTraffic @date,@region_id";
	}

}	

				
 if ($type=="Monthly")
{
	
	if ($branch_id<>"")
	{
	 	$sql = "
		 	SET NOCOUNT ON
	
			DECLARE @date as smalldatetime ;
			DECLARE @region_id as varchar(5);
			DECLARE @branch_id  as varchar(7);
		
				
			SET @date 		= '$date'
			SET @region_id 	= '$region_id'
			SET @branch_id 	= '$branch_id'
					
			EXEC inv05_RptCustTrafficMonthlyByBranch @date,@region_id,@branch_id";
	}		
	else
	{
		$sql = "
		 	SET NOCOUNT ON
	
			DECLARE @date as smalldatetime ;
			DECLARE @region_id as varchar(5);
				
				
			SET @date 		= '$date'
			SET @region_id 	= '$region_id'
		
			
			EXEC inv05_RptCustTrafficMonthly @date,@region_id";
}

}


if ($type=="Weekly")
{
	
	if ($branch_id<>"")
	{
	 	$sql = "
		 	SET NOCOUNT ON
	
			DECLARE @datestart as smalldatetime ;
			DECLARE @dateend as smalldatetime ;
			DECLARE @region_id as varchar(5);
			DECLARE @branch_id  as varchar(7);
		
				
			SET @datestart 	= '$datestart'
			SET @dateend 	= '$dateend'
			SET @region_id 	= '$region_id'
			SET @branch_id 	= '$branch_id'
					
			EXEC inv05_RptCustTrafficWeeklyByBranch @datestart,@dateend,@region_id,@branch_id";
	}		
	else
	{
		$sql = "
		 	SET NOCOUNT ON
	
			DECLARE @datestart as smalldatetime ;
			DECLARE @dateend as smalldatetime ;
			DECLARE @region_id as varchar(5);
				
			SET @datestart 	= '$datestart'
			SET @dateend 	= '$dateend'
			SET @region_id 	= '$region_id'
		
			
			EXEC inv05_RptCustTrafficWeekly @datestart,@dateend,@region_id";
}

}
	$rs = $conn->execute($sql);

 if ($type=="Daily")
 {
  
	while (!$rs->EOF) {
		unset($obj);
		$day					=	(float) trim($rs->fields['day']);
	
		$branch_id				=   trim($rs->fields['branch_id']); 
		$branch_name			=   trim($rs->fields['branch_name']);
		$_walk					= 	(float) trim($rs->fields['custtrafficdetil_W']);
		$_inquery				= 	(float) trim($rs->fields['custtrafficdetil_I']);
		$_purchase				= 	(float) trim($rs->fields['custtrafficdetil_P']);
		$custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		
	
		unset($obj);
		$obj->day				=	$day;
		$branch_id				=   trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'WALK';
		$obj->nilai				=	$_walk;
		$obj->custtraffic_date			=	SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['custtraffic_date']));
		$obj->custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$branch_id				=   trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'INQUERY';
		$obj->nilai				=	$_inquery;
		$obj->custtraffic_date			=	SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['custtraffic_date']));
		$obj->custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$branch_id				=   trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'PURCHASE';
		$obj->nilai				=	$_purchase;
		$obj->custtraffic_date			=	SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['custtraffic_date']));
		$obj->custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		$data[] = $obj;	
			
		$rs->MoveNext();
	}
 
  }
   if ($type=="Monthly")
	{

	 	while (!$rs->EOF) {
		unset($obj);
 
		$obj->month					=	trim($rs->fields['month']);
		$obj->custtrafficdetil_W	=	trim($rs->fields['custtrafficdetil_W']);
		$obj->custtrafficdetil_I	=	trim($rs->fields['custtrafficdetil_I']);
		$obj->custtrafficdetil_P	=	trim($rs->fields['custtrafficdetil_P']);
		$obj->result				=	trim($rs->fields['result']);
		$obj->rate					=	trim($rs->fields['rate']);
		$data[] = $obj;	
			
		$rs->MoveNext();
	}
	}

 if ($type=="Weekly")
 {
  
	while (!$rs->EOF) {
		unset($obj);
		$day					=	trim($rs->fields['day']);
	
		$branch_id				=   trim($rs->fields['branch_id']); 
		$branch_name			=   trim($rs->fields['branch_name']);
		$_walk					= 	(float) trim($rs->fields['custtrafficdetil_W']);
		$_inquery				= 	(float) trim($rs->fields['custtrafficdetil_I']);
		$_purchase				= 	(float) trim($rs->fields['custtrafficdetil_P']);
		$custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		
	
		unset($obj);
		$obj->day				=	$day;
		$branch_id				=   trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'WALK';
		$obj->nilai				=	$_walk;
		$obj->custtraffic_date			=	SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['custtraffic_date']));
		$obj->custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$branch_id				=   trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'INQUERY';
		$obj->nilai				=	$_inquery;
		$obj->custtraffic_date			=	SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['custtraffic_date']));
		$obj->custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$branch_id				=   trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'PURCHASE';
		$obj->nilai				=	$_purchase;
		$obj->custtraffic_date			=	SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['custtraffic_date']));
		$obj->custtrafficdetil_waktu	=	trim($rs->fields['custtrafficdetil_waktu']);
		$data[] = $obj;	
		
		$data[] = $obj;	
		
		$rs->MoveNext();
	}
 
  }
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>