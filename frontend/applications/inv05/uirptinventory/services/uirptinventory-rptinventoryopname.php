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
	
	//SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_opnameproject_id', 'opnameproject_id', " %s = '%s' ");
	$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
	$opnameproject_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_opnameproject_id', '', "{criteria_value}");

}

/* Ambil semua region yang parent nya region_id
$sql = "select * from master_region where region_id='$region_id'";
$rs  = $conn->Execute($sql);
$region_path = $rs->fields['region_path'];
$sql = "select * from master_region where region_path like '$region_path%'";
$rs  = $conn->Execute($sql);
$arrregions = array();
while (!$rs->EOF) {
	$arrregions[] = "region_id='".$rs->fields['region_id']."'";
	$rs->MoveNext();
}
$regions_criteria = implode(" OR ", $arrregions);
$SQL_CRITERIA .= "AND (".$regions_criteria.")"; 
 

$SQL = "
DROP TABLE #temp_id
DROP TABLE #rptOpname
DROP TABLE #rptTEMPOpname
DROP TABLE #Summary
DROP TABLE #FinalSummary
";
$conn->Execute($SQL);
*/
 
//$sql = "EXEC E_FRM2_MGP_OPNAME.dbo.op_RptOpnameDaily '$datestart','$opnameproject_id'";


$sql = "SELECT * FROM E_FRM2_MGP_OPNAME.dbo.transaksi_opnameproject WHERE opnameproject_id = '$opnameproject_id'";
$rs_t = $conn->Execute($sql);
$opname_type  = $rs_t->fields['opnameproject_descr'];

if (strtolower($opname_type)=='regular')
{
$sql = "SELECT * FROM master_iteminventory WHERE left(iteminventory_id,2)= 'TS'  and iteminventory_isdisabled=0 ORDER by iteminventory_id";

 }
 
if (strtolower($opname_type)=='daily')
{
$sql = "SELECT * FROM E_FRM2_MGP_OPNAME.dbo.master_dailyopname WHERE iteminventory_isdisabled = 0"; 
}
  
$rs = $conn->Execute($sql);

$totalCount = $rs->recordCount();


 
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->iteminventory_id 		= trim($rs->fields['iteminventory_id']);
	/*
	$obj->iteminventory_name 	= trim($rs->fields['iteminventory_name']);
	$obj->iteminventory_color 	= trim($rs->fields['iteminventory_color']);
	$obj->iteminventory_size 	= trim($rs->fields['iteminventory_size']);
	$obj->branch_id 			= trim($rs->fields['branch_id']);
	$obj->branch_name 			= trim($rs->fields['branch_name']);
	$obj->BEGIN 				= 1*$rs->fields['BEGIN'];
	$obj->opname 				= 1*$rs->fields['OPNAME'];
	$obj->END 					= 1*$rs->fields['END'];
	*/
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