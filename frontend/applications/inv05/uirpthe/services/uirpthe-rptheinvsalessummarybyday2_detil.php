<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];
	$ids		= $_POST['ids'];
	$tgl		= $_POST['day'];
	

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
		$type = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_type', '', "{criteria_value}");	
		*/
		//$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		
	}

$data = array();

$delimiter = explode ("|",$ids);
$region_id	= $delimiter[0];
$branch_id	= $delimiter[1];
$startdate	= $delimiter[2];
$enddate	= $delimiter[3];
$branch_name = $delimiter[4];
		$sql = "
			SET NOCOUNT ON 
			DECLARE @region_id as varchar(5)
        	DECLARE @dateend as smalldatetime
      		DECLARE @branch_id as varchar(7)
      		DECLARE @branch_name as varchar(20)
      		DECLARE @day as varchar (10)
			 
			SET @region_id ='$region_id'
			SET @dateend = '$enddate'
			SET @branch_id='$branch_id'
			SET @branch_name = '$branch_name'
			SET @day= '$tgl'
			
			EXEC poshe_RptSalesSummary_byDay#2 '$region_id','$enddate','$branch_id','$branch_name','$tgl'";
	
		$rs  = $conn->Execute($sql);

	while (!$rs->EOF) {
		unset($obj);
	
		$day					=	$tgl;
		$branch_id				=   trim($rs->fields['branch_id']);
		$branch_name			=   trim($rs->fields['branch_name']);
				
		unset($obj);
		$obj->day				=	$day;
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'JML BON';
		$obj->nilai				=	(float) trim($rs->fields['_jmlbon']);
		IF ($obj->transtype = 'JML BON')
		{
			$_jmlbon = $obj->nilai;
		}
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'QTY';
		$obj->nilai				=	(float) trim($rs->fields['_qty']);
		IF ($obj->transtype = 'QTY')
		{
			$_qty = $obj->nilai;
		}
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'GROSS';
		$obj->nilai				=	(float) trim($rs->fields['_gross']);
		IF ($obj->transtype = 'GROSS')
		{
			$_gross = $obj->nilai;
		}
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'ITEM NETT';
		$obj->nilai				=	(float) trim($rs->fields['_itemnett']);
		IF ($obj->transtype = 'ITEM NETT')
		{
			$_itemnett = $obj->nilai;
		}
		$data[] = $obj;	
		
		
		unset($obj);
		$obj->day				=	$day;
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'NETT (FULL+DISC)';
		$obj->nilai				=	(float) trim($rs->fields['_nett']);
		IF ($obj->transtype = 'NETT (FULL+DISC)')
		{
			$_nett = $obj->nilai;
		}
		$data[] = $obj;	
		
		
		unset($obj);
		$obj->day				=	$day;
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'NETT FULL';
		$obj->nilai				=	(float) trim($rs->fields['nett_full']);
		IF ($obj->transtype = 'NETT FULL')
		{
			$nett_full = $obj->nilai;
		}
		$data[] = $obj;	
		
		unset($obj);
		$obj->day				=	$day;
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'NETT DISC';
		$obj->nilai				=	(float) trim($rs->fields['nett_disc']);
		IF ($obj->transtype = 'NETT DISC')
		{
			$nett_disc = $obj->nilai;
		}
		$data[] = $obj;	
	
	/* ----------------------- TOTAL ----------------------- */
		
		unset($obj);
		$obj->day				=	' TOTAL';
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'TOTAL JML BON';
		$obj->nilai				=	$_jmlbon;
		$data[] = $obj;
		
		unset($obj);
		$obj->day				=	' TOTAL';
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'TOTAL QTY';
		$obj->nilai				=	$_qty;
		$data[] = $obj;
		
		unset($obj);
		$obj->day				=	' TOTAL';
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'TOTAL GROSS';
		$obj->nilai				=	$_gross;
		$data[] = $obj;
		
		unset($obj);
		$obj->day				=	' TOTAL';
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'TOTAL ITEM NETT';
		$obj->nilai				=	$_itemnett;
		$data[] = $obj;
		
		unset($obj);
		$obj->day				=	' TOTAL';
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'TOTAL NETT (FULL+DISC)';
		$obj->nilai				=	$_nett;
		$data[] = $obj;
		
		unset($obj);
		$obj->day				=	' TOTAL';
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'TOTAL NETT FULL';
		$obj->nilai				=	$nett_full;
		$data[] = $obj;
		
		unset($obj);
		$obj->day				=	' TOTAL';
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		$obj->transtype			=	'TOTAL NETT DISC';
		$obj->nilai				=	$nett_disc;
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