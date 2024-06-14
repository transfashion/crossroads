<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$ids 		= $_POST['ids'];
	$criteria	= $_POST['criteria'];
	
	
	$data = array();
	$fids = explode("|", $ids);
	for ($i=0; $i<count($fids); $i++) {
		$id = $fids[$i];
		
		$sql = "SELECT * FROM master_iteminventory where iteminventory_id='$id'";
		$rs = $conn->Execute($sql);
		
		unset($obj);
		$obj->iteminventory_id = $rs->fields['iteminventory_id'];
		$obj->iteminventory_name = str_replace(array('"', "'", "\\"), array('*','*','*'), $rs->fields['iteminventory_name']);	
		$obj->iteminventory_article = $rs->fields['iteminventory_article'];		
		$obj->iteminventory_material = $rs->fields['iteminventory_material'];
		$obj->iteminventory_color = $rs->fields['iteminventory_color'];
		$obj->iteminventory_size = $rs->fields['iteminventory_size'];
		
		$data[] = $obj;
	
	}
	
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>