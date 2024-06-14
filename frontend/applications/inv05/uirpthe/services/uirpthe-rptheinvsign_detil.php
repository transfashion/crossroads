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
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		$datestart = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
		
	}	



	$sql = "
			SET NOCOUNT ON;

		declare @date as smalldatetime;
		set @date = '$datestart';

        declare @username as varchar(50)
		set @username ='$username'

        select * 
        INTO #region_username
        from master_userregion where username = @username
        
        
        
		select 
		region_id,
		branch_id, 
		branch_name = (SELECT branch_name FROM master_branch WHERE branch_id=A.branch_id)
		into #temp_branch_34
		from master_regionbranch A where regionbranch_disabledentry=0



		select *
		into #temp_sign_34 
		from dbo.transaksi_hepossynsignsvr
		where 
		convert(varchar(10),synsign_dateserver,120)>=convert(varchar(10),@date,120)
		AND convert(varchar(10),synsign_dateserver,120)<=convert(varchar(10),@date,120)
		AND branch_id IN (SELECT branch_id FROM #temp_branch_34)
		 

		BEGIN

			SET NOCOUNT OFF;

		select 
		A.region_id,
		region_name = (select region_name FROM master_region WHERE region_id = A.region_id),
			A.branch_id, A.branch_name,
			SSIGNIN = isnull((SELECT TOP 1 substring(convert(varchar(19),synsign_dateserver,120),12,8) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SIGNIN' ORDER BY synsign_dateserver ASC),''),
			CSIGNIN = isnull((SELECT TOP 1 substring(convert(varchar(19),synsign_dateclient,120),12,8) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SIGNIN' ORDER BY synsign_dateserver ASC),''),
			DSIGNIN = isnull((SELECT TOP 1 DATEDIFF (MINUTE ,synsign_dateserver ,synsign_dateclient ) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SIGNIN' ORDER BY synsign_dateserver ASC),''),
			SSENDDATA = isnull((SELECT TOP 1 substring(convert(varchar(19),synsign_dateserver,120),12,8) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SENDDATA' ORDER BY synsign_dateserver DESC),''), 
			CSENDDATA = isnull((SELECT TOP 1 substring(convert(varchar(19),synsign_dateclient,120),12,8) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SENDDATA' ORDER BY synsign_dateserver DESC),''),
			DSENDDATA = isnull((SELECT TOP 1 DATEDIFF (MINUTE ,synsign_dateserver ,synsign_dateclient ) FROM #temp_sign_34 WHERE region_id = A.region_id AND region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SENDDATA' ORDER BY synsign_dateserver DESC),''),
			SSIGNOFF = isnull((SELECT TOP 1 substring(convert(varchar(19),synsign_dateserver,120),12,8) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SIGNOFF' ORDER BY synsign_dateserver DESC),''),
			CSIGNOFF = isnull((SELECT TOP 1 substring(convert(varchar(19),synsign_dateclient,120),12,8) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SIGNOFF' ORDER BY synsign_dateserver DESC),''),
			DSIGNOFF = isnull((SELECT TOP 1 DATEDIFF (MINUTE ,synsign_dateserver ,synsign_dateclient ) FROM #temp_sign_34 WHERE region_id = A.region_id AND branch_id=A.branch_id AND synsign_type='SIGNOFF' ORDER BY synsign_dateserver DESC),'')
			from #temp_branch_34 A inner join #region_username B on A.region_id = B.region_id 
			--WHERE region_id in (select region_id FROM #region_username)

			SET NOCOUNT ON;

		END


		drop table #temp_branch_34;
		drop table #temp_sign_34;
		drop table #region_username


	";

//print $sql;
	
	
	
	$data = array();
	$rs   = $conn->Execute($sql);
	
	while (!$rs->EOF) {
	   
		unset($obj);
        $obj->region_id				= $rs->fields['region_id'];
        $obj->region_name			= $rs->fields['region_name'];
		$obj->branch_id				= $rs->fields['branch_id'];
		$obj->branch_name			= $rs->fields['branch_name'];
		
		
		$obj->ssignin	= $rs->fields['SSIGNIN'];
		$obj->csignin	=  $rs->fields['CSIGNIN'];
		$obj->dsignin	=  $rs->fields['DSIGNIN'];
		
		$obj->ssenddata	=   $rs->fields['SSENDDATA'];
		$obj->csenddata	=   $rs->fields['CSENDDATA'];
		$obj->dsenddata	=  $rs->fields['DSENDDATA'];
		
 
		$obj->ssignoff	=   $rs->fields['SSIGNOFF'];
		$obj->csignoff	=   $rs->fields['CSIGNOFF'];
		$obj->dsignoff	=   $rs->fields['DSIGNOFF'];


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