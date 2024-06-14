<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];

	

$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
/*		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_contractno', 'employee_contractno', "{criteria_value}");
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_tipekontrak', 'employee_tipekontrak', "{criteria_value}");
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_kontrak', 'employee_kontrak', "{criteria_value}");
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_startdate', 'employee_startdate', "{criteria_value}");
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_enddate', 'employee_enddate', "{criteria_value}");
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $CRITERIA_DB, 'obj_chk_employee_pihak2name', 'employee_pihak2name', "{criteria_value}");
*/	
	$objname = 'obj_chk_employee_contractno';
		$columnname = 'employee_contractno';
		if ($criteria[$objname]->checked) {
			$value = $criteria[$objname]->value;
			$_added_criteria = " $columnname = '$value'";
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
		}

	$objname = 'obj_chk_employee_tipekontrak';
		$columnname = 'employee_tipekontrak';
		if ($criteria[$objname]->checked) {
			$value = $criteria[$objname]->value;
			$_added_criteria = " $columnname = '$value'";
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
		}
	
	$objname = 'obj_chk_employee_kontrak';
		$columnname = 'employee_kontrak';
		if ($criteria[$objname]->checked) {
			$value = $criteria[$objname]->value;
			$_added_criteria = " $columnname = '$value'";
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
		}
		
	$objname = 'obj_chk_employee_startdate';
		$columnname = 'employee_startdate';
		if ($criteria[$objname]->checked) {
			$value = $criteria[$objname]->value;
			$_added_criteria = " $columnname = '$value'";
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
		}
	
	$objname = 'obj_chk_employee_enddate';
		$columnname = 'employee_enddate';
		if ($criteria[$objname]->checked) {
			$value = $criteria[$objname]->value;
			$_added_criteria = " $columnname = '$value'";
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
		}
	
	$objname = 'obj_chk_employee_pihak2name';
		$columnname = 'employee_pihak2name';
		if ($criteria[$objname]->checked) {
			$value = $criteria[$objname]->value;
			$_added_criteria = " employee_pihak2name LIKE '%$value%'";
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
		}
		
	$objname = 'obj_chk_region_id';
		$columnname = 'region_id';
		if ($criteria[$objname]->checked) {
			$value1 = $criteria[$objname]->value;
			
			$SQLA			= 	"SELECT region_id FROM master_region WHERE region_name = '$value1'";
			$rsA 			= 	$conn->execute($SQLA);
			$region_id 		= 	trim($rsA->fields['region_id']);
			$value 			= 	$region_id;
		
			$_added_criteria = " $columnname = '$value'";
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
		}

	}

if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_employee WHERE $SQL_CRITERIA order by employee_tipekontrak";
} else {
	$sql = "SELECT * FROM master_employee order by employee_tipekontrak";
}


$rs = $conn->Execute($sql);

	while (!$rs->EOF) {
		unset($obj);
		
 		$obj->employee_contractno			=	$rs->fields['employee_contractno'];
 		$obj->employee_nik					=	$rs->fields['employee_nik'];
 		$obj->employee_pihak1name			=	$rs->fields['employee_pihak1name'];
		$obj->employee_pihak2name			=	$rs->fields['employee_pihak2name'];
		$obj->employee_pihak1jabatan		=	$rs->fields['employee_pihak1jabatan'];
		$obj->jabatan_id					=	$rs->fields['jabatan_id'];
		$obj->jabatan_name					=	$rs->fields['jabatan_name'];
		$obj->employee_pihak2address		=	$rs->fields['employee_pihak2address'];
		$obj->employee_kontrak				=	SQLUTIL::SQLDateParseToStringdatesmall($rs->fields['employee_kontrak']);
		$obj->employee_startdate			=	SQLUTIL::SQLDateParseToStringdatesmall($rs->fields['employee_startdate']);
		$obj->employee_enddate				=	SQLUTIL::SQLDateParseToStringdatesmall($rs->fields['employee_enddate']);
		$obj->employee_pihak2location		=	$rs->fields['employee_pihak2location'];
		$obj->employee_tipekontrak			=	$rs->fields['employee_tipekontrak'];
		$obj->employee_tunj_jabatan			=	1*$rs->fields['employee_tunj_jabatan'];
		$obj->employee_tunj_telkom			=	1*$rs->fields['employee_tunj_telkom'];
		$obj->employee_tunj_transport		=	1*$rs->fields['employee_tunj_transport'];
		$obj->employee_tunj_harian			=	1*$rs->fields['employee_tunj_harian'];
		$obj->employee_intensif_longshift	=	1*$rs->fields['employee_intensif_longshift'];
		$obj->employee_intensif_longshift	=	1*$rs->fields['employee_intensif_longshift'];
						
		$region_id = trim($rs->fields['region_id']);
		$SQLC					= 	"SELECT region_name FROM master_region WHERE region_id = '$region_id'";
		$rsC 					= 	$conn->execute($SQLC);
		$region_name 			= 	trim($rsC->fields['region_name']);
		$obj->region_id			=	$region_id;
		$obj->region_name		=	$region_name;
        
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