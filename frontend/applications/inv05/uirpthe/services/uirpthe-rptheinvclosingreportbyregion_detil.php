<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];
	$ids		= $_POST['ids'];


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
		$periode_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_periode', '', "{criteria_value}");
		//$branch_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_branch_id', '', "{criteria_value}");
		//$datestart_new = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart_new', '', "{criteria_value}");
		//$dateend_new   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend_new',   '', "{criteria_value}");
		//$datestart_old = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_datestart_old', '', "{criteria_value}");
		//$dateend_old   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend_old',   '', "{criteria_value}");
	
			
	}
	
	set_time_limit(600000);
	$data = array();
	

 
 	$args = explode("|", $ids);
	$region_id = $args[0];
	$periode_id= $args[1];
	$heinvctg_id= $args[2];
 
 
		$sql = "
		SET NOCOUNT ON
		EXEC inv05_RptClosingReport_Region '$region_id','$periode_id','$heinvctg_id'  ";
	$rs  = $conn->Execute($sql);
	
	

	while (!$rs->EOF) {
		unset($obj);
		$obj->branch_id			=	trim($rs->fields['branch_id']);
		$obj->branch_name		=	trim($rs->fields['branch_name']);
		$obj->heinv_id			=	trim($rs->fields['heinv_id']);
		$obj->heinv_art			=	trim($rs->fields['heinv_art']);
		$obj->heinv_mat			=	trim($rs->fields['heinv_mat']);
		$obj->heinv_col			=	trim($rs->fields['heinv_col']);
		$obj->heinv_name		=	trim($rs->fields['heinv_name']);
		$obj->heinvgro_id		=	trim($rs->fields['heinvgro_id']);
		$obj->heinvgro_name		=	trim($rs->fields['heinvgro_name']);
		$obj->heinvctg_id		=	trim($rs->fields['heinvctg_id']);
		$obj->heinvctg_name		=	trim($rs->fields['heinvctg_name']);
		$obj->season_id			=	trim($rs->fields['season_id']);
		$obj->lastcogs			=	(float) trim($rs->fields['LASTCOGS']);
		$obj->qtybegin			=	(float) trim($rs->fields['QTYBEG']);
		$obj->valbegin			=	(float) trim($rs->fields['VALBEG']);
		$obj->qtyrv				=	(float) trim($rs->fields['QTYRV']);
		$obj->valrv				=	(float) trim($rs->fields['VALRV']);
		$obj->cost				=	(float) trim($rs->fields['COST']);
		$obj->qtytro			=	(float) trim($rs->fields['QTYTRO']);
		$obj->valtro			=	(float) trim($rs->fields['VALTRO']);		
		$obj->qtytri			=	(float) trim($rs->fields['QTYTRI']);		
		$obj->valtri			=	(float) trim($rs->fields['VALTRI']);		
		$obj->qtytts			=	(float) trim($rs->fields['QTYTTS']);		
		$obj->valtts			=	(float) trim($rs->fields['VALTTS']);		
		
		$obj->qtysa				=	(float) trim($rs->fields['QTYSA']);
		$obj->salesgross		=	(float) trim($rs->fields['SALESGROSS']);
		$obj->disc				=	(float) trim($rs->fields['disc']);
		$obj->salesnett			=	(float) trim($rs->fields['SALESNETT']);
		$obj->valsa				=	(float) trim($rs->fields['VALSA']);			
		
		
		$obj->qtydo				=	(float) trim($rs->fields['QTYDO']);		
		$obj->valdo				=	(float) trim($rs->fields['VALDO']);			
		$obj->qtyaj				=	(float) trim($rs->fields['QTYAJ']);		
		$obj->valaj				=	(float) trim($rs->fields['VALAJ']);			
		$obj->qtyoth			=	(float) trim($rs->fields['QTYOTH']);		
		$obj->valoth			=	(float) trim($rs->fields['VALOTH']);		
		$obj->qtyend			=	(float) trim($rs->fields['QTYEND']);		
		$obj->valend			=	(float) trim($rs->fields['VALEND']);		
		$obj->ID_group1			=	'';
		$obj->ID_group2			=	trim($rs->fields['season_id']);
		$obj->ID_detil			=	'';

				
		

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