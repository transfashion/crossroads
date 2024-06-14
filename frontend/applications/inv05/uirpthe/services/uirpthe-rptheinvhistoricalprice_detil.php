<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];
	$ids 		= $_POST['ids'];

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
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$heinv_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_tm_id', '', "{criteria_value}");
		$heinv_art = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_art', '', "{criteria_value}");		
	}
	
 
 
	$data = array();

	$args = explode("|", $ids);



"$cid|$heinv_id|$heinv_art|$jumlah_halaman|$limit|$start";

	$cid = $args[0];
	$heinv_id = $args[1];
	$heinv_art = $args[2];
	$jumlah_halaman = $args[3];
	$limit	 = $args[4];
	$start   = $args[5];
	
 
	
 		$sql = "
		 SET NOCOUNT ON


	select * from cache_heinvhistoricalprice where cacheid='$cid' ";
//	$rs  = $conn->Execute($sql);

	$rs = $conn->SelectLimit($sql, $limit, $start);
	
 
 
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->cacheid			=	trim($rs->fields['cacheid']);
		$obj->expired			=	trim($rs->fields['expired']);
		$obj->heinv_id			=	trim($rs->fields['heinv_id']);
		$obj->heinv_art			=	trim($rs->fields['heinv_art']);
		$obj->heinv_mat			=	trim($rs->fields['heinv_mat']);
		$obj->heinv_col			=	trim($rs->fields['heinv_col']);
		$obj->heinv_name		=	trim($rs->fields['heinv_name']);
		$obj->season_id			=	trim($rs->fields['season_id']);
		$obj->price_id			=	trim($rs->fields['price_id']); 
 		$obj->price_startdate	=	trim($rs->fields['price_startdate']); 
 		$obj->region_id			=	trim($rs->fields['region_id']); 
 		$obj->price_descr		=	trim($rs->fields['price_descr']); 
 		$obj->heinv_price01		=	trim($rs->fields['heinv_price01']); 
		$obj->heinv_pricedisc01	=	trim($rs->fields['heinv_pricedisc01']); 
		$obj->rowid				=	trim($rs->fields['rowid']);
 
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