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
		$periode_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_periode', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
        $hidevalue = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'HideValue', '', "{criteria_value}");
        $username  = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'username', '', "{criteria_value}");
			
	}
    
 
	$data = array();
     
            $SQL = "SELECT * FROM master_regionbranch WHERE branch_id = '$branch_id'";
            $rs=$conn->execute($SQL);

        	while (!$rs->EOF) {
            	
                $region_id = $rs->fields['region_id'];
                
                $sqlR = "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
                $rsR = $conn->execute($sqlR);
                $region_name = $rsR->fields['region_name'];
                
                $region_id = $rs->fields['region_id'];
                
                
                $SQLSEASON = "SELECT season_id FROM master_season";
                $rsSeason  = $conn->execute($SQLSEASON);
                
                while (!$rsSeason->EOF) 
                {
                            
                        	unset($obj);
                            $season_id = $rsSeason->fields['season_id'];
                            $sqlauth = "SELECT * from master_userregion WHERE region_id = '$region_id' AND username = '$username'";
                            $rsauth = $conn->execute($sqlauth);
                            $totalcount = $rsauth->recordcount();

                            if ($totalcount)
                            {
                                	$obj->ids = "$region_id|$region_name|$periode_id|$branch_id|$season_id|$hidevalue";
                                	$data[] = $obj;
                                    $rsauth->MoveNext();
                            }
                    $rsSeason->MoveNext();
                }
                
                $rs->MoveNext();
                
        	}
            
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>