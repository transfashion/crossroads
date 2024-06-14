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
	 	$heinvgro_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvgro_id', '', "{criteria_value}");
 		$heinvctg_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvctg_id', '', "{criteria_value}");
		$season_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_sea_id',   '', "{criteria_value}");
 		$hidebasic   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_type_id',   '', "{criteria_value}");
 		$heinv_isdisabled   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_dis_id',   '', "{criteria_value}");		
}





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
	
 
 

$data = array();

$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$cacheid    = $rs->fields['cacheid'];
$jumlah_halaman = 1;
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