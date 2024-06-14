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
		
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
			
	}
 
	$data=array();
	

    $split_startdate	= explode("-", $startdate);
	$startyear 	= $split_startdate [0];
	$startmonth	= (float) $split_startdate [1];
	$startday	= $split_startdate [2];
			
	$split_enddate 		= explode("-", $enddate);
	$endyear	= $split_enddate [0];
	$endmonth	= (float) $split_enddate [1];
	$endday		= $split_enddate [2];
    
    $l = $startyear;
    
    $sql = "SELECT  region_id FROM master_userregion WHERE username = '$username' and userregion_isdisabled=0";
    $rs=$conn->execute($sql);
    WHILE(!$rs->EOF)
    {
        
        $region_id = $rs->fields['region_id'];
        
        $sqlr = "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
        $rsR = $conn->execute($sqlr);
        $region_name = $rsR->fields['region_name'];
        
            $SQLU = "
             select   distinct A.branch_id from master_regionbranch A inner join master_userbranch B ON A.branch_id = B.branch_id 
            inner join master_branch C on A.branch_id = C.branch_id 
            WHERE B.username = '$username' AND A.region_id = '$region_id' AND C.branch_type='OU'";
             $rsB = $conn->execute($SQLU);
            
            
            WHILE (!$rsB->EOF)
            {
                
                $branch_id = $rsB->fields['branch_id'];
                $sqlBranch = "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
                
                $rsBr = $conn->execute($sqlBranch);
                $branch_name =  $rsBr->fields['branch_name'];
        
                
                        /*Cek dulu, Bulannya sama apa enggak */
                        if ($startmonth==$endmonth)
                        {
                            
                                for($l=$startyear; $l<=$endyear; $l++) 
                                {
                                    for($j=$startmonth; $j<=$endmonth; $j++) 
                                    {
                                        for($i=$startday; $i<=$endday; $i++)
                                            {
                                                EXECUTEDAY($region_id,$region_name,$branch_id,$branch_name,$l, $j, $i);
                                                //print   $l . '-' . $j . '-' . $i . '-' . $region_name .'-'. $branch_name . "\r\n";
                                            }
                                    }
                                }    
                        }
                        
                        
                        
                        
                        if ($startmonth<>$endmonth)
                        {
                         
                          for($i=$startmonth; $i<=$endmonth; $i++) 
                          {
                                //print '------->  ' . $i .'------------>' . $endmonth . "\r\n";
                                if ($i==$startmonth)
                               {
                                    for ($l=$startday;$l<=31; $l++)
                                        {
                                            EXECUTEDAY($region_id,$region_name,$branch_id,$branch_name,$startyear, $i, $l);
                                            //print $startyear . '-' . $i . '-' . $l .  ' x ' . $region_name .'-'. $branch_name . "\r\n";
                                        }  
                                         
                                }
                                else
                                {
                                    
                                    if ($i<$endmonth)
                                    {
                                        for ($l=1;$l<=31; $l++)
                                            {
                                                EXECUTEDAY($region_id,$region_name,$branch_id,$branch_name,$startyear, $i, $l);
                                                //print $startyear . '-' . $i . '-' .$l .  ' + ' . $region_name .'-'. $branch_name . "\r\n";
                                            }
                                    }
                                      
                                    if ($i==$endmonth)
                                    {
                                        for ($l=1;$l<=$endday; $l++)
                                            {
                                                EXECUTEDAY($region_id,$region_name,$branch_id,$branch_name,$startyear, $i, $l);
                                                //print $startyear . '-' . $i . '-' .$l . ' = ' . $region_name .'-'. $branch_name . "\r\n";
                                            }
                                    }
                                     
                                            
                                }
                             
                             
                          }  
                        }
                        
                $rsB->MoveNext();                      
            }
    
        $rs->MoveNext();
    }
    
     
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));
	 
	
	
	function EXECUTEDAY($region_id,$region_name,$branch_id,$branch_name,$year, $month, $day) {
	   	global $username;	   
	   	global $conn;	   
		global $data;
		global $region_id;
        global $region_name;
		global $branch_id;
        global $branch_name;
        
		if (checkdate($month,$day,$year)){
			
			$startdate = "$year-$month-$day";
			$enddate = $startdate;
            
    			unset($obj);  
    			$obj->ids ="$region_id|$region_name|$branch_id|$branch_name|$startdate|$enddate  ";            
    			$data[] = $obj;
                            
             
                                                                                                                                                                        
		}
	}
 
?>