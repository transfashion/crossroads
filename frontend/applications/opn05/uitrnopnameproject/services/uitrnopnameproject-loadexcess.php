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
		
		$opnameproject_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'opnameproject_id', '', "{criteria_value}");
			
	}
	

	$data = array();
	 
		$SQL = "
					SET NOCOUNT ON;
				
					declare @opnameproject_id as varchar(30);
					SET @opnameproject_id   	= '$opnameproject_id';
					EXEC opn05he_RptExcess @opnameproject_id
				";
		

		$rs = $conn->Execute($SQL);

 
		while (!$rs->EOF) {

			unset($obj);
			$obj->heinv_id 					= $rs->fields['heinv_id']; 
		 
			$obj->heinv_art 				=  $rs->fields['heinv_art']; 
			$obj->heinv_mat 				=  $rs->fields['heinv_mat']; 
			$obj->heinv_col 				=  $rs->fields['heinv_col']; 
			$obj->heinv_name 				=  $rs->fields['heinv_name']; 
			$obj->heinvitem_size 			=  $rs->fields['heinvitem_size']; 
			$obj->heinvitem_colnum 			=  $rs->fields['heinvitem_colnum']; 			
			$obj->qty 						= (int) $rs->fields['qty']; 
			$obj->qty_reserved 				=  (int)$rs->fields['qty_reserved']; 
			$obj->selisih 					=  (int)$rs->fields['selisih']; 
			$obj->rowid 					=  $rs->fields['rowid']; 
		
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