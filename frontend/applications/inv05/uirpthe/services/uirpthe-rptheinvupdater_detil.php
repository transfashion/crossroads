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
		
		$tbtd = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_tbtd', '', "{criteria_value}");	
	}
	
 

	$data = array();

	$args = $tbtd;

	$sql = "
	SET NOCOUNT ON
	
	DECLARE @tbtd as varchar(255)
	SET @tbtd = '$tbtd'
	EXEC inv05_RptUpdater @tbtd";

	$rs = $conn->execute($sql);

	$totalcount = $rs->recordcount();
 
	
	while (!$rs->EOF) {
		unset($obj);
		$obj->region_id			=	trim($rs->fields['region_id']);
		$obj->region_name		=	trim($rs->fields['region_name']);
		$obj->branch_id			=	trim($rs->fields['branch_id']);
		$obj->branch_name		=	trim($rs->fields['branch_name']);
		$obj->ok				=	trim($rs->fields['ok']);
		
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