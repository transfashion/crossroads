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
 

	 $region_id 	= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
	 $heinvgro_id 	= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_heinvgro_id', '', "{criteria_value}");
	 $heinvctg_id 	= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_heinvctg_id', '', "{criteria_value}");
	 $season_id 	= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_sea_id',   '', "{criteria_value}");
	 $type_id 		= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_type_id',   '', "{criteria_value}");
	 $disabled 		= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_dis_id',   '', "{criteria_value}");			
	 $heinv_id 		= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_inventory_heinv_id',   '', "{criteria_value}");		
	 $hemoving_id 	= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_inventory_hemoving_id',   '', "{criteria_value}");		 
}



/*
$sql = "

SET NOCOUNT ON
	DECLARE @region_id as nvarchar(5)
	DECLARE @heinvgro_id as nvarchar(8)
	DECLARE @heinvctg_id as nvarchar(8)
	DECLARE @season_id as nvarchar(3)
	DECLARE @hidebasic as nvarchar(1)
	DECLARE @heinv_isdisabled as tinyint
 


	SET @region_id ='$region_id'
	SET @heinvgro_id='$heinvgro_id'
	SET @heinvctg_id='$heinvctg_id'
	SET @season_id='$season_id'
	SET @hidebasic='$hidebasic'
	SET @heinv_isdisabled='$heinv_isdisabled'
	EXEC Inv05_RptItem_Flat @region_id,@heinvgro_id,@heinvctg_id,@season_id,@hidebasic,@heinv_isdisabled	";
*/
$sql="
		DECLARE @region_id as nvarchar(5)
		SET @region_id ='$region_id'
		
		DECLARE @heinvgro_id as nvarchar(8)
		SET @heinvgro_id ='$heinvgro_id'
		
		DECLARE @heinvctg_id as nvarchar(8)
		SET @heinvctg_id ='$heinvctg_id'
		
		DECLARE @season_id as nvarchar(3)
		SET @season_id ='$season_id'
		
		DECLARE @type_id as nvarchar(13)
		SET @type_id ='$type_id'
		
		DECLARE @disabled as nvarchar(13)
		SET @disabled ='$disabled'
		

		DECLARE @heinv_id as nvarchar(13)
		SET @heinv_id ='$heinv_id'
		
		DECLARE @hemoving_id as nvarchar(23)
		SET @hemoving_id ='$hemoving_id'
		
		
		
SELECT
A.region_id,
heinv_id, 
heinv_art, 
heinv_mat, 
heinv_col, 
heinv_name, 
heinv_gtype,
heinv_isdisabled,
A.heinvgro_id,
B.heinvgro_name,
A.heinvctg_id,
C.heinvctg_name,
season_id,
heinv_priceori, 
heinv_price01,
heinv_pricedisc01

INTO #TEMP1
from master_heinv A inner join master_heinvgro B on A.heinvgro_id= B.heinvgro_id Inner join master_heinvctg C on A.heinvctg_id = C.heinvctg_id
AND A.region_id = @region_id 


IF(@heinvgro_id<>'')
BEGIN
	DELETE FROM #TEMP1 WHERE heinvgro_id<>@heinvgro_id
END

IF(@heinvctg_id<>'')
BEGIN
	DELETE FROM #TEMP1 WHERE heinvctg_id<>@heinvctg_id
END

IF(@season_id<>'')
BEGIN
	DELETE FROM #TEMP1 WHERE season_id<>@season_id
END

IF(@type_id='True')
BEGIN
	DELETE FROM #TEMP1 WHERE heinv_gtype='F'
END

IF(@disabled='True')
BEGIN
	DELETE FROM #TEMP1 WHERE heinv_isdisabled=1
END

IF(@heinv_id<>'')
BEGIN
	DELETE FROM #TEMP1 WHERE heinv_id<>@heinv_id
END



SELECT * FROM #TEMP1
DROP TABLE #TEMP1 ";


 
$data = array();

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$cacheid    = $rs->fields['cacheid'];
$jumlah_halaman = 10;
$limit = ceil($totalCount/$jumlah_halaman);  
for ($i=0; $i<$jumlah_halaman; $i++) {
	unset($obj);
	$start = $i*$limit; 
	$obj->ids = "$cacheid|$jumlah_halaman|$limit|$start";
	$data[] = $obj;
}


$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>