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
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_branch_id', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_dateend', '', "{criteria_value}");
		$heinvgro_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvgro_id', '', "{criteria_value}");
		$heinvctg_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_heinvctg_id', '', "{criteria_value}");
		$art_switcher = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_type', '', "{criteria_value}");
		$branch_city   	 = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_area_id', '', "{criteria_value}");
	}
set_time_limit(6000);
if ($branch_city!='')
{
	$SQL_MAIN = "select * from master_regionmember A inner join master_branch B on 
		A.mbranch_id  = B.branch_id 
		where A.region_id= '$region_id' and B.branch_city = '$branch_city'";	

}
else
{
	$SQL_MAIN = "SELECT * FROM master_regionmember where region_id = '$region_id'";

}



$rs = $conn->Execute($SQL_MAIN);
$totalCount = $rs->recordCount();

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
			global $group;
			global $category;
			global $art_switcher;
			if (checkdate($month,$day,$year)){
				//PRINT "$year|$month|$day\n";
				$startdate = "$year-$month-$day";
				$enddate = $startdate;
				unset($obj); 
				$obj->ids = "$region_id|$branch_id|$startdate|$enddate|$group|$category|$art_switcher|$branch_city";
				$data[] = $obj;
			}
		}

?>
