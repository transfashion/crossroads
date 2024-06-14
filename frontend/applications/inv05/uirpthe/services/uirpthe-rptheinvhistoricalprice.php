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
		$heinv_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_tm_id', '', "{criteria_value}");
		$heinv_art = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_art', '', "{criteria_value}");		
				
	}

 
 
  		$SQL = "SELECT cid = newid()";
		$rs = $conn->Execute($SQL);
	
		$cid = $rs->fields['cid'];
	
		$data = array();

if ($heinv_id)
{
 		$sql = "
		SET NOCOUNT ON
		
		DECLARE @region_id as varchar(5)
		DECLARE @cacheid as varchar (50)
		DECLARE @heinv_id as varchar (30)
		
		SET @region_id = '$region_id'
		SET @cacheid = '$cid'
		SET @heinv_id = '$heinv_id'
			
		EXEC inv05_RptHistoricalPriceID @region_id,@cacheid,@heinv_id";

}	
else
{
	if ($heinv_art)
	{
 		$sql = "
		SET NOCOUNT ON
		
		DECLARE @region_id as varchar(5)
		DECLARE @cacheid as varchar (50)
		DECLARE @heinv_art as varchar (20)
		
		SET @region_id = '$region_id'
		SET @cacheid = '$cid'
		SET @heinv_art = '$heinv_art'
			
		EXEC inv05_RptHistoricalPriceART @region_id,@cacheid,@heinv_art";
	}
	else
	{
		$sql = "
		SET NOCOUNT ON
		
		DECLARE @region_id as varchar(5)
		DECLARE @cacheid as varchar (50)
		
		SET @region_id = '$region_id'
		SET @cacheid = '$cid';
			
		EXEC inv05_RptHistoricalPrice @region_id,@cacheid";
	}		
}
	
 
	

		
		
		$rs  = $conn->Execute($sql);
	
//			$totalCount = $rs->recordCount();
//			$cacheid    = $cid;

 		$totalCount = $rs->recordCount();
 		$cacheid    = $rs->fields['cid'];
		
		$jumlah_halaman = 100;
		
		$limit = ceil($totalCount/$jumlah_halaman);  
	
		
		
		for ($i=0; $i<$jumlah_halaman; $i++){	 
		unset($obj);
		$start = $i*$limit; 
		$obj->ids = "$cid|$heinv_id|$heinv_art|$jumlah_halaman|$limit|$start";
		$data[] = $obj;
		} 
 
		
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>