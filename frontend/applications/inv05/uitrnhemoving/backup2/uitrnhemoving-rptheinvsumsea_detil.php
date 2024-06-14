<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$ids 		= $_POST['ids'];
	$criteria	= $_POST['criteria'];
	$includeconsumable = $_POST['includeconsumable']=='True' ? 1 : 0;
		
	
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		
		$coverage  = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_coverage',  '', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		$dateend   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend',   '', "{criteria_value}");
		
	}	
	
	
	
	//A52332F2-51A9-485D-8D81-C2BDD3A7ACB4|10|32|0
	$args = explode("|", $ids);
	$cacheid = $args[0];
	$page    = $args[1];
	$limit	 = $args[2];
	$start   = $args[3];


	$sql = "select distinct season_group from cache_heinvsummary
			where
			cacheid = '$cacheid'
			AND REPSECTION='ITEM' 	 
			AND season_group NOT IN (' OLD', 'BASIC')
			order by season_group DESC
			";


	$rsS = $conn->Execute($sql);		

	
	$sqlSEA1 = "";
	$sqlSEA2 = "";
	$otherseason = array();
	$seasonname = array();

	$num = 0;
	while (!$rsS->EOF) {
		$num++;
		$colnum = str_pad($num, 2, "0", STR_PAD_LEFT);
		
		$season_id = $rsS->fields['season_group'];
		if ($num<=10) {
			$colname   = "SEACOL".$colnum;
			$sqlSEA1  .= "		[$colname] = SUM(A.[$colname]),\n";
			
			$seasonname[$colname] = $season_id;
		} else {
			$colname   = "OTHER".$colnum;
			$otherseason[] =  "SUM(A.[$colname])"; 
		}	
	
		$sqlSEA2 .= "[$colname] = CASE WHEN season_group='$season_id' THEN CAST([END] as decimal) ELSE 0 END,\n";
	
		$rsS->MoveNext();
	}
	
	
	$OTHERSEASONADD = "";
	if (count($otherseason) > 0) {
		$OTHERSEASONADD = "+" . implode("+", $otherseason);
	}


	$sql = "

		select
		A.region_id, B.region_name, A.heinvgro_name, A.heinvctg_namegroup,
		$sqlSEA1
		[BASIC] = SUM(A.[SEABASIC]),
		[OLD] = SUM(A.[SEA OLD]) $OTHERSEASONADD,
		[QTY] = SUM(A.[QTY])
		FROM (
				select 
				A.region_id, A.heinvgro_name, A.heinvctg_namegroup,

				$sqlSEA2

				[SEA OLD] = CASE WHEN season_group=' OLD' THEN CAST([END] as decimal) ELSE 0 END,
				[SEABASIC] = CASE WHEN season_group='BASIC' THEN CAST([END] as decimal) ELSE 0 END,
				QTY = CAST([END] as decimal)
				from dbo.cache_heinvsummary  A inner join master_heinvgro B on A.heinvgro_id=B.heinvgro_id and A.region_id=B.region_id
				WHERE 
					A.cacheid = '$cacheid'
				AND A.REPSECTION='ITEM' 
				AND B.heinvgro_isconsumable = 0
		) A LEFT JOIN master_region B on A.region_id = B.region_id
		GROUP BY A.region_id, B.region_name, A.heinvgro_name, A.heinvctg_namegroup
		ORDER BY B.region_name, A.heinvgro_name, A.heinvctg_namegroup
		
	";

	
	$rs = $conn->Execute($sql);
	$data = array();
	
	
	//print $sql; 
	//die();

	unset($obj);
	for ($i=1; $i<=10; $i++) {
		$colnum  = str_pad($i, 2, "0", STR_PAD_LEFT);
		$colname = "SEACOL".$colnum;
		$colname_obj_v = "seacol".$colnum;
		$colname_obj_s = "seacol".$colnum."name";
		$obj->{$colname_obj_s} = $seasonname[$colname];
		$obj->{$colname_obj_v} = 0;
		$obj->region_id = "_NONE_"; 
		$obj->region_name = "_NONE_"; 
		$obj->heinvgro_name = ""; 
		$obj->heinvctg_namegroup = ""; 				
	}
	$data[] = $obj;
	
			
	while (!$rs->EOF) {
		unset($obj);
		$obj->region_id = $rs->fields['region_id']; 
		$obj->region_name = $rs->fields['region_name']; 
		$obj->heinvgro_name = $rs->fields['heinvgro_name']; 
		$obj->heinvctg_namegroup = $rs->fields['heinvctg_namegroup']; 

		$obj->qty = (float) $rs->fields['QTY']; 
		$obj->basic = (float) $rs->fields['BASIC']; 
		$obj->old = (float) $rs->fields['OLD']; 
		
		$obj->seacol01name = ""; 
		$obj->seacol02name = ""; 
		$obj->seacol03name = ""; 
		$obj->seacol04name = ""; 
		$obj->seacol05name = ""; 
		$obj->seacol06name = ""; 
		$obj->seacol07name = ""; 
		$obj->seacol08name = ""; 
		$obj->seacol09name = ""; 
		$obj->seacol10name = ""; 		
		
		$obj->seacol01 = (float) $rs->fields['SEACOL01']; 
		$obj->seacol02 = (float) $rs->fields['SEACOL02']; 
		$obj->seacol03 = (float) $rs->fields['SEACOL03']; 
		$obj->seacol04 = (float) $rs->fields['SEACOL04']; 
		$obj->seacol05 = (float) $rs->fields['SEACOL05']; 
		$obj->seacol06 = (float) $rs->fields['SEACOL06']; 
		$obj->seacol07 = (float) $rs->fields['SEACOL07']; 
		$obj->seacol08 = (float) $rs->fields['SEACOL08']; 
		$obj->seacol09 = (float) $rs->fields['SEACOL09']; 
		$obj->seacol10 = (float) $rs->fields['SEACOL10']; 

		
		$data[] = $obj;
		$rs->MoveNext();
	}
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = count($data);
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>
