<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];
	$ids		= $_POST['ids'];

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
		/*
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_city = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_area', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		*/

	}
	
$delimiter 		= explode("|",$ids);
$region_id 		= $delimiter[0];
$branch_id 		= $delimiter[1];
$branch_city 	= $delimiter[2];
$enddate		= $delimiter[3];

	$data = array();

 
		$sql = "
		SET NOCOUNT ON
		
		DECLARE @region_id as varchar(5)
		DECLARE @branch_id as varchar(7)
		DECLARE @branch_city as varchar (20)
		DECLARE @enddate as smalldatetime;
		
		SET @region_id ='$region_id'
		SET @branch_id ='$branch_id'
		SET @branch_city='$branch_city'
		SET @enddate = '$enddate';

		EXEC poshe_RptSalesSummarybymonth '$region_id','$branch_id','$branch_city','$enddate'";

			$rs  = $conn->Execute($sql);


		
	while (!$rs->EOF) {
		unset($obj);
		$obj->branch_id			=	trim($rs->fields['branch_id']);
		$obj->branch_name		=	trim($rs->fields['branch_name']);
		$obj->branch_city		=	trim($rs->fields['branch_city']);
		
		$obj->qty_1				=	(float) trim($rs->fields['1_qty']);
		$obj->gross_1			=	(float) trim($rs->fields['1_gross']);
		$obj->itemnett_1		=	(float) trim($rs->fields['1_itemnett']);
		$obj->nett_1			=	(float) trim($rs->fields['1_nett']);
			
		$obj->qty_2				=	(float) trim($rs->fields['2_qty']);
		$obj->gross_2			=	(float) trim($rs->fields['2_gross']);
		$obj->itemnett_2		=	(float) trim($rs->fields['2_itemnett']);
		$obj->nett_2			=	(float) trim($rs->fields['2_nett']);
		
		$obj->qty_3				=	(float) trim($rs->fields['3_qty']);
		$obj->gross_3			=	(float) trim($rs->fields['3_gross']);
		$obj->itemnett_3		=	(float) trim($rs->fields['3_itemnett']);
		$obj->nett_3			=	(float) trim($rs->fields['3_nett']);
		
		$obj->qty_4				=	(float) trim($rs->fields['4_qty']);
		$obj->gross_4			=	(float) trim($rs->fields['4_gross']);
		$obj->itemnett_4		=	(float) trim($rs->fields['4_itemnett']);
		$obj->nett_4			=	(float) trim($rs->fields['4_nett']);
		
		$obj->qty_5				=	(float) trim($rs->fields['5_qty']);
		$obj->gross_5			=	(float) trim($rs->fields['5_gross']);
		$obj->itemnett_5		=	(float) trim($rs->fields['5_itemnett']);
		$obj->nett_5			=	(float) trim($rs->fields['5_nett']);
		
		$obj->qty_6				=	(float) trim($rs->fields['6_qty']);
		$obj->gross_6			=	(float) trim($rs->fields['6_gross']);
		$obj->itemnett_6		=	(float) trim($rs->fields['6_itemnett']);
		$obj->nett_6			=	(float) trim($rs->fields['6_nett']);
		
		$obj->qty_7				=	(float) trim($rs->fields['7_qty']);
		$obj->gross_7			=	(float) trim($rs->fields['7_gross']);
		$obj->itemnett_7		=	(float) trim($rs->fields['7_itemnett']);
		$obj->nett_7			=	(float) trim($rs->fields['7_nett']);
		
		$obj->qty_8				=	(float) trim($rs->fields['8_qty']);
		$obj->gross_8			=	(float) trim($rs->fields['8_gross']);
		$obj->itemnett_8		=	(float) trim($rs->fields['8_itemnett']);
		$obj->nett_8			=	(float) trim($rs->fields['8_nett']);
		
		$obj->qty_9				=	(float) trim($rs->fields['9_qty']);
		$obj->gross_9			=	(float) trim($rs->fields['9_gross']);
		$obj->itemnett_9		=	(float) trim($rs->fields['9_itemnett']);
		$obj->nett_9			=	(float) trim($rs->fields['9_nett']);
		
		$obj->qty_10			=	(float) trim($rs->fields['10_qty']);
		$obj->gross_10			=	(float) trim($rs->fields['10_gross']);
		$obj->itemnett_10		=	(float) trim($rs->fields['10_itemnett']);
		$obj->nett_10			=	(float) trim($rs->fields['10_nett']);
		
		$obj->qty_11			=	(float) trim($rs->fields['11_qty']);
		$obj->gross_11			=	(float) trim($rs->fields['11_gross']);
		$obj->itemnett_11		=	(float) trim($rs->fields['11_itemnett']);
		$obj->nett_11			=	(float) trim($rs->fields['11_nett']);
		
		$obj->qty_12			=	(float) trim($rs->fields['12_qty']);
		$obj->gross_12			=	(float) trim($rs->fields['12_gross']);
		$obj->itemnett_12		=	(float) trim($rs->fields['12_itemnett']);
		$obj->nett_12			=	(float) trim($rs->fields['12_nett']);

		$obj->qty				=	(float) $obj->qty_1 + $obj->qty_2 + $obj->qty_3+ $obj->qty_4+ $obj->qty_5+ $obj->qty_6+ $obj->qty_7+ $obj->qty_8+ $obj->qty_9+ $obj->qty_10+ $obj->qty_11+ $obj->qty_12;
		$obj->gross				=	(float) $obj->gross_1 + $obj->gross_2+ $obj->gross_3+ $obj->gross_4+ $obj->gross_5+ $obj->gross_6+ $obj->gross_7+ $obj->gross_8+ $obj->gross_9+ $obj->gross_10+ $obj->gross_11+ $obj->gross_12;
		$obj->itemnett			=	(float) $obj->itemnett_1 + $obj->itemnett_2+ $obj->itemnett_3+ $obj->itemnett_4+ $obj->itemnett_5+ $obj->itemnett_6+ $obj->itemnett_7+ $obj->itemnett_8+ $obj->itemnett_9+ $obj->itemnett_10+ $obj->itemnett_11+ $obj->itemnett_12;
		$obj->nett				=	(float) $obj->nett_1 + $obj->nett_2+ $obj->nett_3+ $obj->nett_4+ $obj->nett_5+ $obj->nett_6+ $obj->nett_7+ $obj->nett_8+ $obj->nett_9+ $obj->nett_10+ $obj->nett_11+ $obj->nett_12;
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