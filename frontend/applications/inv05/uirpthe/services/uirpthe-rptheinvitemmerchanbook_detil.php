<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$ids 		= $_POST['ids'];
	$criteria	= $_POST['criteria'];
	$includeconsumable = $_POST['includeconsumable']=='True' ? 1 : 0;
		
	
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
 		$heinv_gtype   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_type_id',   '', "{criteria_value}");
 		$heinv_isdisabled   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_dis_id',   '', "{criteria_value}");		
 		$heinv_id 		= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_inventory_heinv_id',   '', "{criteria_value}");		
		$hemoving_id 	= SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_search_chk_inventory_hemoving_id',   '', "{criteria_value}");		 

	}	
	
	
	
	//A52332F2-51A9-485D-8D81-C2BDD3A7ACB4|10|32|0
	$args = explode("|", $ids);
	$cacheid = $args[0];
	$page    = $args[1];
	$limit	 = $args[2];
	$start   = $args[3];
	
if ($heinv_isdisabled)
{
	$heinv_isdisabled=1;
}
else
{
 $heinv_isdisabled=0;
 } 


if ($heinv_gtype)
{
	$heinv_gtype='B';
}
else
{
 $heinv_gtype='F';
 } 



	$sql = "
	DECLARE @region_id as nvarchar(5)
	DECLARE @heinvgro_id as nvarchar(8)
	DECLARE @heinvctg_id as nvarchar(8)
	DECLARE @season_id as nvarchar(3)
	DECLARE @heinv_gtype as nvarchar(1)
	DECLARE @heinv_isdisabled as tinyint
	DECLARE @heinv_id as nvarchar(13)
 

SET NOCOUNT ON
	SET @region_id ='$region_id'
	SET @heinvgro_id='$heinvgro_id'
	SET @heinvctg_id='$heinvctg_id'
	SET @season_id='$season_id'
	SET @heinv_gtype='$heinv_gtype'
	SET @heinv_isdisabled='$heinv_isdisabled'
	SET @heinv_id='$heinv_id'
	EXEC Inv05_RptItem_MerchanBook @region_id,@heinvgro_id,@heinvctg_id,@season_id,@heinv_gtype,@heinv_isdisabled,@heinv_id	";
	
	
	//$rs = $conn->Execute($sql);
	
	$rs = $conn->SelectLimit($sql, $limit, $start);
 
 
	$data = array();
	
	while (!$rs->EOF) {
		unset($obj);
$obj->heinv_id			= $rs->fields['heinv_id'];
$obj->heinv_art			= $rs->fields['heinv_art']; 
$obj->heinv_mat			= $rs->fields['heinv_mat']; 
$obj->heinv_col			= $rs->fields['heinv_col']; 
$obj->heinv_name		= $rs->fields['heinv_name']; 
$obj->heinv_gtype		= $rs->fields['heinv_gtype']; 
$obj->heinv_isdisabled	= 1*$rs->fields['heinv_isdisabled'];
$obj->heinvgro_id		= $rs->fields['heinvgro_id'];
$obj->heinvgro_name		= $rs->fields['heinvgro_name']; 
$obj->heinvctg_id		= $rs->fields['heinvctg_id'];
$obj->heinvctg_name		= $rs->fields['heinvctg_name']; 
$obj->season_id			= $rs->fields['season_id'];
$obj->firstprice			= (float) $rs->fields['firstprice'];
$obj->currentprice			= (float) $rs->fields['currentprice']; 
$obj->currentdisc			= (float) $rs->fields['currentdisc'];
$obj->region_id			= $rs->fields['region_id'];




//$str = dirname(__FILE__) . "/../../../../../../imghost/$id.jpg" ;
	
	$id = $rs->fields['heinv_id'];
	$str = dirname(__FILE__) . "/../../../../../../imghost/$id.jpg" ;
 
	
if (is_file($str))
{
 	 $url = "http://webservice.transmahagaya.com/imghost/$id.jpg"; 

}
else
{
	  $url = "http://webservice.transmahagaya.com/imghost/NOIMAGE.JPG";
};
  	 
	   
	   
	   $obj->imagelocation	= $url; 
  	 
  	 

 
		
		$data[] = $obj;
		$rs->MoveNext();
	}
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = count($data);
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>