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
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		$type = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_type', '', "{criteria_value}");
		$customer_name = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_name', '', "{criteria_value}");
	}
	
	 
	$data = array();

if ($type==" 1. Sales By age and Gender")
{
	if ($branch_id<>"")
	{
		$sql= "
			set nocount on
			
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @branch_id as varchar(7);

			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
			set @branch_id = '$branch_id';
			
			EXEC PosHe_RptSalesSummaryByCustomer_1ByBranch @region_id,@startdate,@enddate,@branch_id;
			";		
 			
//print $sql; 

//print 'test->'. $totalCount;
//die;
	}
	else 
	{
		$sql= "
			set nocount on
			
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @branch_id as varchar(7);
		
			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
			
		
			EXEC PosHe_RptSalesSummaryByCustomer_1 @region_id,@startdate,@enddate,@branch_id;
			";		
	}
}	
  
if ($type==" 2. Sales By Customer Name")
{
	if ($branch_id <> "")
	{
		$sql= "
			set nocount on
			
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @branch_id as varchar(7);
		
			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
			set @branch_id = '$branch_id';
			
			EXEC PosHe_RptSalesSummaryByCustomer_2ByBranch @region_id,@startdate,@enddate,@branch_id;
			";		
	}
	else 
	{
		$sql= "
			set nocount on
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @branch_id as varchar(7);
		
			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
		
			EXEC PosHe_RptSalesSummaryByCustomer_2 @region_id,@startdate,@enddate,@branch_id;
			";		
	}
}


if ($type==" 3. Sales By Card Holder")
{	if ($branch_id <> "")
	{
		$sql= "
			set nocount on
			
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @branch_id as varchar(7);
		
			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
			set @branch_id = '$branch_id';
			
			EXEC PosHe_RptSalesSummaryByCustomer_3ByBranch @region_id,@startdate,@enddate,@branch_id;
			";		
	}
	else 
	{
		$sql= "
			set nocount on
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @branch_id as varchar(7);
		
			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
		
			EXEC PosHe_RptSalesSummaryByCustomer_3 @region_id,@startdate,@enddate,@branch_id;
			";		
				
	}
}
 

if ($type==" 4. Sales By Customer History")
{
	if ($branch_id <> "")
	{
		$sql= "
			set nocount on
			
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @branch_id as varchar(7);

			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
			set @branch_id = '$branch_id';
			
			EXEC PosHe_RptSalesSummaryByCustomer_4ByBranch @region_id,@startdate,@enddate,@branch_id;
			";		
	}
	else 
	{
		$sql= "
			set nocount on
			
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
		
		
			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
				
			EXEC PosHe_RptSalesSummaryByCustomer_4 @region_id,@startdate,@enddate;
			";		
	}
}

if ($type==" 5. Sales By Customer Name #2")
{
	if ($branch_id <> "")
	{
		$sql= "
			set nocount on
	
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @customer_name as varchar(50)
			declare @branch_id as varchar(7);

			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
			set @customer_name = '$customer_name';
			set @branch_id = '$branch_id';
			
			EXEC PosHe_RptSalesSummaryByCustomer_5ByBranch @region_id,@startdate,@enddate,@branch_id,@customer_name;
			";		
	}
	else 
	{
		$sql= "
			set nocount on
			
			declare @region_id as varchar(5);
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			declare @customer_name as varchar(50)
		
			set @region_id = '$region_id';
			set @startdate = '$startdate';
			set @enddate = '$enddate';
			set @customer_name = '$customer_name';
				
			EXEC PosHe_RptSalesSummaryByCustomer_5 @region_id,@startdate,@enddate,@customer_name;
			";		
	}
}
		
		
		
		
		 
		$rs  = $conn->Execute($sql);
		$totalCount = $rs->recordCount();

//print $totalCount;
 
if ($type==" 1. Sales By age and Gender")
{
 	while (!$rs->EOF) {
		unset($obj);
		
		$obj->customer_gendername	=	trim($rs->fields['customer_gendername']);
 		$obj->customer_agename		=	trim($rs->fields['customer_agename']);
 		
		$branch_id 				= 	trim($rs->fields['branch_id']);
        $SQLB					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsB 					= $conn->execute($SQLB);
		$branch_name 			= 	trim($rsB->fields['branch_name']);
		
		$obj->branch_id			=	$branch_name;
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->_gross			=	trim($rs->fields['_gross']);
		$obj->nett				=	trim($rs->fields['nett']);
		$data[] = $obj;
 
		$rs->MoveNext();
		}
}

if ($type==" 2. Sales By Customer Name")
{
 	while (!$rs->EOF) {
		unset($obj);
		
		$obj->customer_id		=	trim($rs->fields['customer_id']);
		$obj->customer_name		=	trim($rs->fields['customer_name']);		
	
		$branch_id 				= 	trim($rs->fields['branch_id']);
        $SQLC					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsC 					= $conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);		
		$obj->branch_id			=	trim($rsC->fields['branch_name']);
		
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->_gross			=	trim($rs->fields['_gross']);
		$obj->nett				=	trim($rs->fields['nett']);
		$data[] = $obj;
 
		$rs->MoveNext();
		}
}

if ($type==" 3. Sales By Card Holder")
{
 	while (!$rs->EOF) {
		unset($obj);
		
		$obj->payment_cardnumber	=	trim($rs->fields['payment_cardnumber']);
		$obj->payment_cardholder	=	trim($rs->fields['payment_cardholder']);
		$obj->pospayment_bank	=	trim($rs->fields['pospayment_bank']);
		
		$branch_id 				= 	trim($rs->fields['branch_id']);
        $SQLB					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsB 					= $conn->execute($SQLB);
		$branch_name 			= 	trim($rsB->fields['branch_name']);
		
		$obj->branch_id			=	$branch_name;
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->_gross			=	trim($rs->fields['_gross']);
		$obj->nett				=	trim($rs->fields['nett']);
		$data[] = $obj;
 
		$rs->MoveNext();
	
		}
}

if ($type==" 4. Sales By Customer History")
{
	while (!$rs->EOF) {
		unset($obj);
		
		$obj->payment_cardnumber	=	trim($rs->fields['payment_cardnumber']);
		$obj->payment_cardholder	=	trim($rs->fields['payment_cardholder']);
		$obj->pospayment_bank	=	trim($rs->fields['pospayment_bank']);
		
		$branch_id 				= 	trim($rs->fields['branch_id']);
        $SQLB					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsB 					= $conn->execute($SQLB);
       	$branch_name 			= 	trim($rsB->fields['branch_name']);
		$obj->branch_id			=	$branch_name;
		
		$obj->customer_name		=	trim($rs->fields['customer_name']);
		$obj->bon_date			=	trim($rs->fields['bon_date']);
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->_gross			=	trim($rs->fields['_gross']);
		$obj->nett				=	trim($rs->fields['nett']);
		$data[] = $obj;
 
		$rs->MoveNext();	
		}
}
		
if ($type==" 5. Sales By Customer Name #2")
{
	while (!$rs->EOF) {
		unset($obj);
		
		$obj->customer_name		=	trim($rs->fields['customer_name']);
		$obj->payment_cardnumber	=	trim($rs->fields['payment_cardnumber']);
		$obj->pospayment_bank	=	trim($rs->fields['pospayment_bank']);
		$obj->pospayment_name	=	trim($rs->fields['pospayment_name']);
		
		$branch_id 				= 	trim($rs->fields['branch_id']);
        $SQLB					= "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
        $rsB 					= $conn->execute($SQLB);
		$branch_name 			= 	trim($rsB->fields['branch_name']);
		$obj->branch_id			=	$branch_name;
		
		$obj->bon_id			=	trim($rs->fields['bon_id']);
		$obj->bon_date			=	trim($rs->fields['bon_date']);
		$obj->bondetil_art		=	trim($rs->fields['bondetil_art']);
		$obj->bondetil_mat		=	trim($rs->fields['bondetil_mat']);
		$obj->bondetil_col		=	trim($rs->fields['bondetil_col']);
		$obj->bondetil_size		=	trim($rs->fields['bondetil_size']);
		$obj->heinv_id			=	trim($rs->fields['heinv_id']);
		$obj->qty				=	1*trim($rs->fields['qty']);
		$obj->_gross			=	trim($rs->fields['_gross']);
		$obj->nett				=	trim($rs->fields['nett']);
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