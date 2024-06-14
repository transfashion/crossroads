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
		

		$bon_region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_location_region_id', '', "{criteria_value}");
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");		
		$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'branch_id', '', "{criteria_value}");
		$date = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart', '', "{criteria_value}");
	}
 
 
	$data = array();

 
		$sql = "
		 SET NOCOUNT ON

	 
		DECLARE @enddate as smalldatetime;
		
 
		SET @enddate = '$date';

		EXEC poshe_RptSalesSummaryByCard '$bon_region_id','$region_id','$branch_id',@enddate  ";
		$rs  = $conn->Execute($sql);
 
 
		
	while (!$rs->EOF) {
		unset($obj);
		$obj->day				=	trim($rs->fields['tgl']);
		$obj->MEGACASH			=	(float) trim($rs->fields['MEGACASH']);		
		$obj->MEGA				=	(float) trim($rs->fields['MEGA']);
		$obj->BCA				=	1*trim($rs->fields['BCA']);
		$obj->BCA_D				=	1*trim($rs->fields['BCA_D']);
		$obj->OTHER				=	1*trim($rs->fields['OTHER']);
		$obj->CASH				=	1*trim($rs->fields['CASH']);
		$obj->AMEX				=	1*trim($rs->fields['AMEX']);		
		$obj->VOUCHER			=	1*trim($rs->fields['VOUCHER']);		
		$obj->REDEEM			=	1*trim($rs->fields['REDEEM']);		
		$obj->ADDDISC			=	1*trim($rs->fields['ADDDISC']);		
		$obj->TOTAL				=	1*trim($rs->fields['TOTAL']);		
		$obj->TYPE				=	trim($rs->fields['TYPE']);		
		$obj->REGION_ID			=	trim($rs->fields['REGION_ID']);		
		$obj->QTY				=	1*trim($rs->fields['qty']);		
		$obj->itemgrossori		=	1*trim($rs->fields['itemgrossori']);		
		$obj->itemgross			=	1*trim($rs->fields['itemgross']);		
		$obj->itemnett			=	1*trim($rs->fields['itemnett']);		
 		$obj->nett				=	1*trim($rs->fields['nett']);		
 		$obj->disc				=	1*trim($rs->fields['disc']);		
 		$obj->SEQ				=	trim($rs->fields['SEQ']);
		 if ($obj->SEQ==1)
		 {
			$obj->n_QTY					=	1*trim($rs->fields['qty']);		
			$obj->n_itemgrossori		=	1*trim($rs->fields['itemgrossori']);		
			$obj->n_itemgross			=	1*trim($rs->fields['itemgross']);		
			$obj->n_itemnett			=	1*trim($rs->fields['itemnett']);		
 			$obj->n_nett				=	1*trim($rs->fields['nett']);		
		  	$obj->n_disc				=	1*trim($rs->fields['disc']);
		  }
		  else
		  {
   			$obj->n_QTY					=	0;		
			$obj->n_itemgrossori		=	0;
			$obj->n_itemgross			=	0;
			$obj->n_itemnett			=	0;
 			$obj->n_nett				=	0;
			$obj->n_disc				=	0;
		   }
 		$obj->region_name		=	trim($rs->fields['region_name']);		

		$data[] = $obj;
 
		$rs->MoveNext();
	}
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>