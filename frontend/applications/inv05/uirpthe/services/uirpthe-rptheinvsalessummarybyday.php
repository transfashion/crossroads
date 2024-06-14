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
//		$branch_city = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_area', '', "{criteria_value}");
		$startdate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
			
	}


	
if ($branch_id<>"")
{
 	$sql = "select B.branch_id from master_regionbranch A inner join master_branch B
	on A.branch_id = B.branch_id where A.region_id = '$region_id' and B.branch_id = '$branch_id'";
}
else
{
	
		$sql = "select B.branch_city from master_regionbranch A inner join master_branch B
		on A.branch_id = B.branch_id where A.region_id = '$region_id'";	
	
}	



	$split_startdate	= explode("-", $startdate);
	$startyear 	= $split_startdate [0];
	$startmonth	= $split_startdate [1];
	$startday	= $split_startdate [2];
			
	$split_enddate 		= explode("-", $enddate);
	$endyear	= $split_enddate [0];
	$endmonth	= $split_enddate [1];
	$endday		= $split_enddate [2];
	
	$data = array();

		for($l=$startyear; $l<=$endyear; $l++) 
		{
			for($j=$startmonth; $j<=$endmonth; $j++) 
			{
				for($i=1; $i<=$endday; $i++) 
				{				 	
					if ($j>=$startmonth)
					{
						if($i>=$startday)
						{
							EXECUTEDAY($sql,$l, $j, $i);
						}
					}
					else
					{
						if($j<$endmonth)
						{
							EXECUTEDAY($sql,$l, $j, $i);
						}
						else
						{
							if ($i<=$endday)
							{
								EXECUTEDAY($sql,$l, $j, $i);
							}
						}
					}												
				}
			}
		}
	
	
/* 	
		unset($obj);
		$obj->branch_id	= $rs->fields['branch_id'];
		$obj->ids = "$region_id|$branch_id|$startdate|$enddate|$branch_city";
		$obj->day = 1;
		$obj->name = "test";
		$data[] = $obj;


	$data = array();
	unset($obj);
	$obj->ids = "$region_id|$enddate";
	$data[] = $obj;
*/

	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
	
	print(stripslashes(json_encode($objResult)));


	function EXECUTEDAY($sql,$year,$month,$day) {
		global $data;
		global $region_id;
		global $branch_id;
		//global $branch_city;
		global $conn;
		if (checkdate($month,$day,$year)){
		 	//PRINT "$year|$month|$day\n";
		 	$rs = $conn->Execute($sql);		 	
			$startdate = "$year-$month-$day";
			$enddate = $startdate;
			
			unset($obj); 
//			$obj->ids = "$region_id|$branch_id|$startdate|$enddate|$branch_city";
			$obj->ids = "$region_id|$branch_id|$startdate|$enddate";
			$obj->day = $day;
			$data[] = $obj;

		}
	}
?>