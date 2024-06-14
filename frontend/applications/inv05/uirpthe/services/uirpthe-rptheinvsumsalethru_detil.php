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
			$sqlSEA1  .= "		[$colname"."SL"."] = SUM(A.[$colname"."SL"."]),\n";
			$sqlSEA1  .= "		[$colname"."RV"."] = SUM(A.[$colname"."RV"."]),\n";

			
			$seasonname[$colname] = $season_id;
		} else {
			$colname   = "OTHER".$colnum;
			$otherseason[] =  "SUM(A.[$colname])"; 
			$otherseason_sl[] =  "SUM(A.[$colname"."SL"."])"; 
			$otherseason_rv[] =  "SUM(A.[$colname"."RV"."])"; 
			
			
		}	
	
		$sqlSEA2 .= "[$colname] = CASE WHEN season_group='$season_id' THEN CAST([END] as decimal) ELSE 0 END,\n";
		$sqlSEA2 .= "[$colname"."SL"."] = CASE WHEN season_group='$season_id' THEN CAST([SL] as decimal) ELSE 0 END,\n";
		$sqlSEA2 .= "[$colname"."RV"."] = CASE WHEN season_group='$season_id' THEN CAST([RV] as decimal) ELSE 0 END,\n";	
		
		$rsS->MoveNext();
	}
	
	
	$OTHERSEASONADD = "";
	if (count($otherseason) > 0) {
		$OTHERSEASONADD = "+" . implode("+", $otherseason);
		$OTHERSEASONADD_SL = "+" . implode("+", $otherseason_sl);
		$OTHERSEASONADD_RV = "+" . implode("+", $otherseason_rv);
	}


	$sql = "

		select
		A.region_id, B.region_name, A.heinvgro_name, A.heinvctg_namegroup,
		$sqlSEA1
		[BASIC] = SUM(A.[SEABASIC]),
		[BASIC_SL] = SUM(A.[SEABASIC_SL]),
		[BASIC_RV] = SUM(A.[SEABASIC_RV]),


		[OLD] = SUM(A.[SEA OLD]) $OTHERSEASONADD,
		[OLD_SL] = SUM(A.[SEA OLD_SL]) $OTHERSEASONADD_SL,
		[OLD_RV] = SUM(A.[SEA OLD_RV]) $OTHERSEASONADD_RV,

		[QTY] = SUM(A.[QTY]),
		[QTY_SL] = SUM(A.[QTY_SL]),
		[QTY_RV] = SUM(A.[QTY_RV])


		FROM (
				select 
				region_id, heinvgro_name, heinvctg_namegroup,

				$sqlSEA2

				[SEA OLD] = CASE WHEN season_group=' OLD' THEN CAST([END] as decimal) ELSE 0 END,
				[SEA OLD_SL] = CASE WHEN season_group=' OLD' THEN CAST([SL] as decimal) ELSE 0 END,
				[SEA OLD_RV] = CASE WHEN season_group=' OLD' THEN CAST([RV] as decimal) ELSE 0 END,
				
				[SEABASIC] = CASE WHEN season_group='BASIC' THEN CAST([END] as decimal) ELSE 0 END,
				[SEABASIC_SL] = CASE WHEN season_group='BASIC' THEN CAST([SL] as decimal) ELSE 0 END,
				[SEABASIC_RV] = CASE WHEN season_group='BASIC' THEN CAST([RV] as decimal) ELSE 0 END,
				
				QTY = CAST([END] as decimal),
				QTY_SL = CAST([SL] as decimal),
				QTY_RV = CAST([RV] as decimal)	
							
				from dbo.cache_heinvsummary
				WHERE 
				cacheid = '$cacheid'
				AND REPSECTION='ITEM' 
		) A LEFT JOIN master_region B on A.region_id = B.region_id
		GROUP BY A.region_id, B.region_name, A.heinvgro_name, A.heinvctg_namegroup
		ORDER BY B.region_name, A.heinvgro_name, A.heinvctg_namegroup
		
	";

	
	$rs = $conn->Execute($sql);
	$data = array();
	
 

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
		$obj->qty_sl = (float) $rs->fields['QTY_SL']; 
		$obj->qty_rv = (float) $rs->fields['QTY_RV']; 
		
		$obj->basic = (float) $rs->fields['BASIC']; 
		$obj->basic_sl = (float) $rs->fields['BASIC_SL'];
		$obj->basic_rv = (float) $rs->fields['BASIC_RV'];
		
		IF ($rs->fields['BASIC_SL']==0 || $rs->fields['BASIC']==0)
		{

			$obj->basicpersen =   0 ;  	 	
		}
		else
		{
			$obj->basicpersen =  (int) (($rs->fields['BASIC_SL']/abs($rs->fields['BASIC']))*100*-1); 	

		}
		
		
		
		$obj->old = (float) $rs->fields['OLD']; 
		$obj->old_sl = (float) $rs->fields['OLD_SL']; 
		$obj->old_rv = (float) $rs->fields['OLD_RV']; 
		
		IF ($rs->fields['OLD_SL']==0 || $rs->fields['OLD']==0)
		{
			$obj->oldpersen =   0 ;  	 	
		}
		else
		{
			$obj->oldpersen =  (int) (($rs->fields['OLD_SL']/abs($rs->fields['OLD']))*100*-1); 	
		}
		
		
		
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
		
		$obj->seacol01 		 = (float) $rs->fields['SEACOL01']; 
		$obj->seacol01sl 	 = (float) $rs->fields['SEACOL01SL']; 
		$obj->seacol01rv 	 = (float) $rs->fields['SEACOL01RV']; 
		
		IF ($rs->fields['SEACOL01SL']==0 && $rs->fields['SEACOL01']==0)
		{
			$obj->seacol01persen =   0 ;  	 	
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL01SL']);
			$_END     = ((float) $rs->fields['SEACOL01']) < 0 ? 0 : (float) $rs->fields['SEACOL01'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol01persen = floor($_PERCENT);
		}
	
		
		$obj->seacol02 = (float) $rs->fields['SEACOL02']; 
		$obj->seacol02sl = (float) $rs->fields['SEACOL02SL']; 
		$obj->seacol02rv = (float) $rs->fields['SEACOL02RV']; 
		
		IF ($rs->fields['SEACOL02SL']==0 || $rs->fields['SEACOL02']==0)
		{
			$obj->seacol02persen =   0 ;  
		}
		else
		{
			
			$_SALES   = abs((float) $rs->fields['SEACOL02SL']);
			$_END     = ((float) $rs->fields['SEACOL02']) < 0 ? 0 : (float) $rs->fields['SEACOL02'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol02persen = floor($_PERCENT);
		}
		
		
		$obj->seacol03 = (float) $rs->fields['SEACOL03']; 
		$obj->seacol03sl = (float) $rs->fields['SEACOL03SL']; 
		$obj->seacol03rv = (float) $rs->fields['SEACOL03RV']; 
		
		
		IF ($rs->fields['SEACOL03SL']==0 || $rs->fields['SEACOL03']==0)
		{
			$obj->seacol03persen =   0 ;  		
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL03SL']);
			$_END     = ((float) $rs->fields['SEACOL03']) < 0 ? 0 : (float) $rs->fields['SEACOL03'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol03persen = floor($_PERCENT);
		}
		
		
		
		$obj->seacol04 = (float) $rs->fields['SEACOL04']; 
		$obj->seacol04sl = (float) $rs->fields['SEACOL04SL']; 
		$obj->seacol04rv = (float) $rs->fields['SEACOL04RV']; 
		
		IF ($rs->fields['SEACOL04SL']==0 || $rs->fields['SEACOL04']==0)
		{
			$obj->seacol04persen =   0 ;  
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL04SL']);
			$_END     = ((float) $rs->fields['SEACOL04']) < 0 ? 0 : (float) $rs->fields['SEACOL04'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol04persen = floor($_PERCENT);		
		}
		
		
			
		$obj->seacol05 = (float) $rs->fields['SEACOL05']; 
		$obj->seacol05sl = (float) $rs->fields['SEACOL05SL']; 
		$obj->seacol05rv = (float) $rs->fields['SEACOL05RV']; 
		
		
		IF ($rs->fields['SEACOL05SL']==0 || $rs->fields['SEACOL05']==0)
		{
			$obj->seacol05persen =  0 ;  
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL05SL']);
			$_END     = ((float) $rs->fields['SEACOL05']) < 0 ? 0 : (float) $rs->fields['SEACOL05'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol05persen = floor($_PERCENT);		
		}
		
		
		$obj->seacol06 = (float) $rs->fields['SEACOL06']; 
		$obj->seacol06sl = (float) $rs->fields['SEACOL06SL']; 
		$obj->seacol06rv = (float) $rs->fields['SEACOL06RV']; 
		
		IF ($rs->fields['SEACOL06SL']==0 || $rs->fields['SEACOL06']==0)
		{
			$obj->seacol06persen =  0 ;  		
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL06SL']);
			$_END     = ((float) $rs->fields['SEACOL06']) < 0 ? 0 : (float) $rs->fields['SEACOL06'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol06persen = floor($_PERCENT);
		}
		
		
		$obj->seacol07 = (float) $rs->fields['SEACOL07']; 
		$obj->seacol07sl = (float) $rs->fields['SEACOL07SL']; 
		$obj->seacol07rv = (float) $rs->fields['SEACOL07RV']; 
		
		IF ($rs->fields['SEACOL07SL']==0 || $rs->fields['SEACOL07']==0)
		{
			$obj->seacol07persen =  0 ; 
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL07SL']);
			$_END     = ((float) $rs->fields['SEACOL07']) < 0 ? 0 : (float) $rs->fields['SEACOL07'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol07persen = floor($_PERCENT); 
		}
		
		
		$obj->seacol08 = (float) $rs->fields['SEACOL08']; 
		$obj->seacol08sl = (float) $rs->fields['SEACOL08SL']; 
		$obj->seacol08rv = (float) $rs->fields['SEACOL08RV']; 
				
		IF ($rs->fields['SEACOL08SL']==0 || $rs->fields['SEACOL08']==0)
		{
			$obj->seacol08persen =   0 ;  
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL08SL']);
			$_END     = ((float) $rs->fields['SEACOL08']) < 0 ? 0 : (float) $rs->fields['SEACOL08'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol08persen = floor($_PERCENT); 
		}
		
		
		
		$obj->seacol09 = (float) $rs->fields['SEACOL09']; 
		$obj->seacol09sl = (float) $rs->fields['SEACOL09SL']; 
		$obj->seacol09rv = (float) $rs->fields['SEACOL09RV']; 

		IF ($rs->fields['SEACOL09SL']==0 || $rs->fields['SEACOL09']==0)
		{
			$obj->seacol09persen =   0 ;  
		}
		else
		{
			$_SALES   = abs((float) $rs->fields['SEACOL09SL']);
			$_END     = ((float) $rs->fields['SEACOL09']) < 0 ? 0 : (float) $rs->fields['SEACOL09'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol09persen = floor($_PERCENT); 
		}
		
		
		
				
		$obj->seacol10 = (float) $rs->fields['SEACOL10']; 
		$obj->seacol10sl = (float) $rs->fields['SEACOL10SL']; 
		$obj->seacol10rv = (float) $rs->fields['SEACOL10RV']; 

		IF ($rs->fields['SEACOL10SL']==0 || $rs->fields['SEACOL10']==0)
		{
			$obj->seacol10persen =   0 ;  
		}
		else
		{
 			$_SALES   = abs((float) $rs->fields['SEACOL10SL']);
			$_END     = ((float) $rs->fields['SEACOL10']) < 0 ? 0 : (float) $rs->fields['SEACOL10'];
			$_BEG     = $_SALES + $_END;
			$_PERCENT = $_BEG==0 ? 0 : ($_SALES / $_BEG)*100 ;
			$obj->seacol10persen = floor($_PERCENT);
		}
		
		
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