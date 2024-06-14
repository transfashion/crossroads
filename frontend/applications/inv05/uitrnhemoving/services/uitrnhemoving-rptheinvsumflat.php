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
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
		$ctg_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_ctgid',   '', "{criteria_value}");
		
}


/* Ambil semua region yang parent nya region_id*/
$sql = "
	SET NOCOUNT ON;

	declare @date_start as smalldatetime;
	declare @date_end as smalldatetime;
	declare @region_id as varchar(5);
	declare @branch_id as varchar(7);
	declare @ctg_id as varchar(20);
	
	SET @region_id = '$region_id'
	SET @branch_id = '$branch_id'; 
	SET @date_start = '$datestart';
	SET @date_end   = '$dateend';
	SET @ctg_id = '$ctg_id';
	
	
	EXEC inv05he_RptSummaryByBranch_FLAT @date_start, @date_end, @region_id, @branch_id, @ctg_id,0, NULL, 0
";

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