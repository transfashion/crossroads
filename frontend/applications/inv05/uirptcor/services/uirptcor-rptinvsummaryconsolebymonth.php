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
    
 
 $months  = array(
					array('JAN', '2012-01-01', '2012-01-31',1),
					array('FEB', '2012-02-01', '2012-02-29',2),
					array('MAR', '2012-03-01', '2012-03-31',3),
					array('APR', '2012-04-01', '2012-04-30',4),
					array('MEI', '2012-05-01', '2012-05-31',5),
                    array('JUN', '2012-06-01', '2012-06-30',6),
                    array('JUL', '2012-07-01', '2012-07-31',7),
                    array('AUG', '2012-08-01', '2012-08-31',8),
                    array('SEP', '2012-09-01', '2012-09-30',9),
                    array('OKT', '2012-10-01', '2012-10-31',10),
                    array('NOV', '2012-11-01', '2012-11-30',11),
                    array('DES', '2012-12-01', '2012-12-31',12)
                    );
                    
                    
                    
                    
	$data = array();
     
  	  foreach ($months as $montharr)
	  {
		$moname    = $montharr[0];
		$datestart = $montharr[1];
		$dateend   = $montharr[2];
        $blnurut   = $montharr[3];
        
        
        if (substr($datestart,5,2)<= substr($periode_id,3,2))
        {
                
                
            $SQL = "SELECT * FROM master_region  WHERE region_isdisabled = 0";
            $rs=$conn->execute($SQL);

        	while (!$rs->EOF) {
                $region_id = $rs->fields['region_id'];
                
                //$region_id = '00700';
                        
                $sqlR = "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
                $rsR = $conn->execute($sqlR);
                $region_name = $rsR->fields['region_name'];
                $region_id = $rs->fields['region_id'];
                
                        	unset($obj);
                            $sqlauth = "SELECT * from master_userregion WHERE region_id = '$region_id' AND username = '$username'";
                            $rsauth = $conn->execute($sqlauth);
                            $totalcount = $rsauth->recordcount();
                            if ($totalcount)
                            {
                                	$obj->ids = "$region_id|$region_name|$periode_id|$datestart|$dateend|$moname|$hidevalue|$blnurut";
                                	$data[] = $obj;
                                    $rsauth->MoveNext();
                            }
                $rs->MoveNext();    
            }
        }
     }
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>