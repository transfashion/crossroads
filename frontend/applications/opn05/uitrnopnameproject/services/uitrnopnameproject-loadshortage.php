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
					EXEC opn05he_RptShortAge @opnameproject_id
				";
		

		$rs = $conn->Execute($SQL);

		while (!$rs->EOF) {

			unset($obj);
			$obj->hemoving_id 			= $rs->fields['hemoving_id']; 
			$obj->hemovingdetil_line 	= (int) $rs->fields['hemovingdetil_line']; 
			$obj->heinvitem_colnum 		=  $rs->fields['heinvitem_colnum']; 
			$obj->heinv_id 				=  $rs->fields['heinv_id']; 
			$obj->heinv_box 			=  $rs->fields['heinv_box']; 
			$obj->qty_B 				=   $rs->fields['qty_B']; 
			$obj->qty_C 				=    $rs->fields['qty_C']; 
			$obj->rowid 				=  $rsI->fields['rowid']; 
		
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