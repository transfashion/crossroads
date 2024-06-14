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
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		$branch_city = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_area', '', "{criteria_value}");
		$type = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_type', '', "{criteria_value}");
	}
	
	
if ($branch_id<>"")
{
 	$sql = "select B.branch_id,B.branch_name from master_regionbranch A inner join master_branch B
	on A.branch_id = B.branch_id where A.region_id = '$region_id' and B.branch_id = '$branch_id'";
}
else
{
	if ($branch_city<>"")
	{
	 	$sql = "select B.branch_id,B.branch_name from master_regionbranch A inner join master_branch B
		on A.branch_id = B.branch_id where A.region_id = '$region_id' and B.branch_city = '$branch_city'";		
	}
	else
	{
		$sql = "select B.branch_id,B.branch_name from master_regionbranch A inner join master_branch B
		on A.branch_id = B.branch_id where A.region_id = '$region_id'";	
	}
}	


	//$rs  = $conn->Execute($sql);
	//$totalCount = $rs->recordCount();
	//print $totalCount;
	$split_startdate	= explode("-", $startdate);
	$startyear 	= $split_startdate [0];
	$startmonth	= $split_startdate [1];
	$startday	= $split_startdate [2];
			
	$split_enddate 		= explode("-", $enddate);
	$endyear	= $split_enddate [0];
	$endmonth	= $split_enddate [1];
	$endday		= $split_enddate [2];

	$data=array();
	
			if ($startmonth<>$endmonth)
{
		for($l=$startyear; $l<=$endyear; $l++) 
			{
				for($j=$startmonth; $j<=$endmonth; $j++) 
				{
					for($i=1; $i<=31; $i++) 
					{
						if ($j==$startmonth)
						{
							if($i>=$startday)
							{
								EXECUTEDAY($l, $j, $i);
							}
							
						}
						else
						{
							if($j<$endmonth)
							{
								EXECUTEDAY($l, $j, $i);
							}
							else
							{
								if ($i<=$endday)
								{
									EXECUTEDAY($l, $j, $i);
								}
							}
						}							
					}
				}
			}
	}
	else
	{
		for($l=$startyear; $l<=$endyear; $l++) 
			{
				for($j=$startmonth; $j<=$endmonth; $j++) 
				{
					for($i=1; $i<=$endday; $i++) 
					{
						if ($j==$startmonth)
						{
							if($i>=$startday)
							{
								EXECUTEDAY($l, $j, $i);
							}
							
						}
						else
						{
							if($j<$endmonth)
							{
								EXECUTEDAY($l, $j, $i);
							}
							else
							{
								if ($i<=$endday)
								{
									EXECUTEDAY($l, $j, $i);
								}
							}
						}							
					}
				}
			}
	}

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));


	function EXECUTEDAY($year, $month, $day) {
			global $data;
			global $region_id;
			global $branch_id;
			global $branch_city;
			global $type;
			if (checkdate($month,$day,$year)){
				//PRINT "$year|$month|$day\n";
				$startdate = "$year-$month-$day";
				$enddate = $startdate;
				unset($obj); 
				$obj->ids = "$region_id|$branch_id|$startdate|$enddate|$branch_city|$type";
				$data[] = $obj;
			}
		}


?>