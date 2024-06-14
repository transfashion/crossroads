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
		$season_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_season_id', '', "{criteria_value}");
		
}


				$SQLCATCH = "SELECT cid=newid()"; 
				$rscatch = $conn->Execute($SQLCATCH);
				$cid = substr($rscatch->fields['cid'],1,strlen($rscatch->fields['cid'])-2);



	/* Ambil semua region yang parent nya region_id*/
	if ($branch_id) {
	
			$sql = "
				SET NOCOUNT ON;
			
				declare @date_start as smalldatetime;
				declare @date_end as smalldatetime;
				declare @region_id as varchar(5);
				declare @branch_id as varchar(7);
				declare @heinvgro_id as varchar(30);
				declare @heinvctg_id as varchar(30);
				declare @season_id as varchar(30);
				declare @cid as varchar(50);
								
				SET @region_id = '$region_id'
				SET @branch_id = '$branch_id'; 
				SET @heinvgro_id = '$heinvgro_id';
				SET @heinvctg_id = '$heinvctg_id';
				SET @season_id = '$season_id';
				SET @date_start = '$datestart';
				SET @date_end   = '$dateend';
			 	SET @cid   = '$cid';
				
				
				EXEC inv05he_RptSummaryByBranchCtg @date_start, @date_end, @region_id, @branch_id, @heinvgro_id, @heinvctg_id, @season_id, 1, @cid, 0
			";	
		  $conn->Execute($sql);
	
	} else {



				$sql = "SELECT * FROM master_regionbranch WHERE region_id = '$region_id'  ";
				$rsB  = $conn->Execute($sql);
	
		
				while (!$rsB->EOF) {
					$branch_id = $rsB->fields['branch_id'];
			
	 
					$sql = "
					SET NOCOUNT ON;
				
					declare @date_start as smalldatetime;
					declare @date_end as smalldatetime;
					declare @region_id as varchar(5);
					declare @branch_id as varchar(7);
					declare @heinvgro_id as varchar(30);
					declare @heinvctg_id as varchar(30);
					declare @season_id as varchar(30);
					declare @cid as varchar(50);
									
					SET @region_id = '$region_id'
					SET @branch_id = '$branch_id'; 
					SET @heinvgro_id = '$heinvgro_id';
					SET @heinvctg_id = '$heinvctg_id';
					SET @season_id = '$season_id';
					SET @date_start = '$datestart';
					SET @date_end   = '$dateend';
 					SET @cid   = '$cid';				
				
					EXEC inv05he_RptSummaryByBranchCtg @date_start, @date_end, @region_id, @branch_id, @heinvgro_id, @heinvctg_id, @season_id,1, @cid, 0";
				  $conn->Execute($sql);
				 
			$rsB->MoveNext();
		}
			
	}


	//print $sql;

	$data = array();
	$ERROR = false;

	try {
		$sql = "
		select * from dbo.cache_heinvsummary 
		WHERE cacheid='$cid'";

		$rs=$conn->Execute($sql);
		$totalCount = $rs->recordCount();
		$cacheid    = $rs->fields['cacheid'];
 
		if ($totalCount>100) {
			$jumlah_halaman = 10;
		} else {
			$jumlah_halaman = 1;
		}
		$limit = ceil($totalCount/$jumlah_halaman);  
		for ($i=0; $i<$jumlah_halaman; $i++) {
			unset($obj);
			$start = $i*$limit; 
			$obj->ids = "$cacheid|$jumlah_halaman|$limit|$start";
			$data[] = $obj;
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