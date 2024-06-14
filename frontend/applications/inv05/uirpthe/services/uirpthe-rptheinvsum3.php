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
	
		$region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend     = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
		$heinvgro_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvgro_id', '', "{criteria_value}");
		$heinvctg_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvctg_id', '', "{criteria_value}");

		
}


$SQL = "SELECT cid = newid()";
$rs = $conn->Execute($SQL);

$cid = $rs->fields['cid'];
//$cid = 'A52332F2-51A9-485D-8D81-C2BDD3A7ACB4';


 IF ($branch_id!='')
 {
  			$sql = "
				SET NOCOUNT ON;
			
				declare @date_start as smalldatetime;
				declare @date_end as smalldatetime;
				declare @region_id as varchar(5);
				declare @branch_id as varchar(7);
				declare @heinvgro_id as varchar(30);
				declare @heinvctg_id as varchar(30);
								
				SET @region_id = '$region_id'
				SET @branch_id = '$branch_id'; 
				SET @heinvgro_id = '$heinvgro_id';
				SET @heinvctg_id = '$heinvctg_id';
				SET @date_start = '$datestart';
				SET @date_end   = '$dateend';
 
				EXEC inv05he_RptSummaryByBranchCtg @date_start, @date_end, @region_id, @branch_id, @heinvgro_id, @heinvctg_id,'', 0, '$cid', 0
			";	
 			$conn->Execute($sql);
  }
  
  else
  
  {
   

$SQL = "SELECT * FROM master_regionmember where region_id = '$region_id'";
$rs = $conn->Execute($SQL);
 
 
 

 
 
while (!$rs->EOF)
{

 			$branch_id = $rs->fields['mbranch_id'];
 
			$sql = "
				SET NOCOUNT ON;
			
				declare @date_start as smalldatetime;
				declare @date_end as smalldatetime;
				declare @region_id as varchar(5);
				declare @branch_id as varchar(7);
				declare @heinvgro_id as varchar(30);
				declare @heinvctg_id as varchar(30);
								
				SET @region_id = '$region_id'
				SET @branch_id = '$branch_id'; 
				SET @heinvgro_id = '$heinvgro_id';
				SET @heinvctg_id = '$heinvctg_id';
				SET @date_start = '$datestart';
				SET @date_end   = '$dateend';
 
				EXEC inv05he_RptSummaryByBranchCtg @date_start, @date_end, @region_id, @branch_id, @heinvgro_id, @heinvctg_id,'', 0, '$cid', 0
			";	
 			$conn->Execute($sql);
 			$rs->MoveNext();
 			
 			
 			

 }
   }
   
   

	$data = array();
	$ERROR = false;

	try {


	
		$totalCount = $rs->recordCount();
		$cacheid    = $cid;

				$SQL_b = "SELECT * FROM master_regionmember where region_id = '$region_id'";
				$rs_b = $conn->Execute($SQL_b);

				while (!$rs_b->EOF)
				{
							$branch_id = $rs_b->fields['mbranch_id'];

							unset($obj);
							$obj->ids = "$cacheid|$branch_id";
							$data[] = $obj;
							

							$rs_b->MoveNext();
				}

	
	} catch (exception $e) {
		$ERROR = $e->GetMessage();	
	}

	$objResult = new WebResultObject("objResult");
	if (!$ERROR) {
		$objResult->totalCount = $totalCount;
		$objResult->success = true;
		$objResult->data = $data;
		unset($objResult->errors); 
	} else {
		$objResult->totalCount = $totalCount;
		$objResult->success = true;
		$objResult->data = $data;
		$objResult->errors = $ERROR;
	}

		
	print(stripslashes(json_encode($objResult)));

?>