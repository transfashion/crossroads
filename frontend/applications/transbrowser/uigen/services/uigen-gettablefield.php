<?php
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
			
			/* Default Criteria */
			/*
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemovingtype_id', 'hemovingtype_id', " %s = '%s' ");
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_id', 'hemoving_id', "refParser");
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_descr', 'hemoving_descr', " {db_field} LIKE '%{criteria_value}%' ");
			*/
		
		
			$table_name = SQLUTIL::BuildCriteria(&$param, $criteria, 'table_name', '', "{criteria_value}");
		
		}
		
		
		
		
		$SQL = "
			SELECT 
			column_id = A.colid, 
			column_name = A.name, 
			column_type = B.name, 
			column_length = A.length, 
			column_xprec  = A.xprec, 
			column_xscale = A.xscale, 
			column_xtype  = (CASE 	WHEN B.xtype in (48, 52, 56, 59, 60, 62, 106, 108, 122, 127) then 'numeric' 			
									WHEN B.xtype in (58, 61) then 'datetime' 			
									WHEN B.xtype in (167, 175, 231, 239) then 'string' 		ELSE ''	END)
			FROM syscolumns A inner join systypes B on A.xtype = B.xtype AND B.name <> 'sysname'
			where object_name(id) = '$table_name' 
		
		";
		
		
	
		$rs = $conn->Execute($SQL);
		$totalCount = $rs->recordCount();
		$data = array();
		
		
		while (!$rs->EOF) {
			unset($obj);
			$obj->column_name = $rs->fields['column_name'];
			$obj->column_type = $rs->fields['column_type'];
			$obj->column_length = $rs->fields['column_length'];
			$obj->column_xprec = $rs->fields['column_xprec'];
			//$obj->column_xtype = $rs->fields['column_xtype'];
			//$obj->column_xtype = 'Textbox';
			
			
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