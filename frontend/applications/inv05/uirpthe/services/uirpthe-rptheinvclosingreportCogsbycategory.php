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
		$periode_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_periode', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
	}
	
//inv05_RptClosingReportCogs_Season
	
			$data = array();
			$sBranch = explode("|",$branch_id);
			
 
			 
						for ($i = 0; $i <= count($sBranch)-2; $i++) 
						{
							$branch_id = explode(";",$sBranch[$i]);
							
							
								
								$sqlB = "Select branch_name FROM master_branch WHERE branch_id= '$branch_id[0]'";
								$rsB = $conn->execute($sqlB);
								$branch_name = $rsB->fields['branch_name'];
								
						
								$sqlS = "SELECT heinvctg_id,heinvctg_name FROM master_heinvctg where region_id = '$region_id'";
								$rsS = $conn->execute($sqlS);
									WHILE (!$rsS->EOF)
										{
										 			$heinvctg_id = $rsS->fields['heinvctg_id'];
												 	$heinvctg_name = $rsS->fields['heinvctg_name'];
													unset($obj);
													$obj->ids = "$region_id|$periode_id|$branch_id[0]|$heinvctg_id|$heinvctg_name|$branch_name|";
													$data[] = $obj;
											$rsS->MoveNext();
										}
									
						
						}
							
					 

	 	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>