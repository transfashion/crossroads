<?php

class SQLUTIL {

	static function SQLDateParseToStringdate($sqldate) {
		if ($sqldate) {
		
			$r  = explode(" ", $sqldate);
			$dt = explode("-", $r[0]);
			$tm	= explode(":", $r[1]);
			
			$year	= $dt[0] ? $dt[0] : 1800;
			$month	= $dt[1] ? $dt[1] : 1;
			$day	= $dt[2] ? $dt[2] : 1;
			$hour	= $tm[0] ? $tm[0] : 0;
			$minute	= $tm[1] ? $tm[1] : 0;
			$second	= $tm[2] ? $tm[2] : 0;
			
			if (!checkdate($month,$day,$year)) {
				$year = 1800;
				$month = 1;
				$day = 1;	
				$hour = 0;
				$minute = 0;
				$second = 0;
			}
			
			return "$day/$month/$year $hour:$minute:$second";

		} else {
			return null;
		}
	}	


	static function SQLDateParseToStringdatesmall($sqldate) {
		if ($sqldate) {
		
			$r  = explode(" ", $sqldate);
			$dt = explode("-", $r[0]);
			$tm	= explode(":", $r[1]);
			
			$year	= $dt[0] ? $dt[0] : 1800;
			$month	= $dt[1] ? $dt[1] : 1;
			$day	= $dt[2] ? $dt[2] : 1;
			$hour	= $tm[0] ? $tm[0] : 0;
			$minute	= $tm[1] ? $tm[1] : 0;
			$second	= $tm[2] ? $tm[2] : 0;
			
			if (!checkdate($month,$day,$year)) {
				$year = 1800;
				$month = 1;
				$day = 1;	
				$hour = 0;
				$minute = 0;
				$second = 0;
			}
			
			return "$day/$month/$year";

		} else {
			return null;
		}
	}
	
	static function SQL_GetNowDate() {
		return date("Y-m-d H:i:s");
	}


	static function SQL_InsertFromArray($tablename, $data) {
		if (!is_array($data)) return;
		while (list($name, $value) = each($data)) {
			$fields[] = $name; 	
		}
		
		$_FIELDS  = implode(", ", $fields);
		$_VALUES  = implode("', '", $data);
		$SQL	 = " INSERT INTO $tablename ";
		$SQL	.= " ($_FIELDS) "; 
		$SQL	.= " VALUES ";
		$SQL	.= " ('$_VALUES') ";		
		
		return $SQL;	
	}

	static function SQL_InsertFromObject($tablename, $obj) {
		$file = "sqlutil.log.txt";
		$fp = fopen($file, "w");
		fputs($fp, "TABLE [$tablename]\n");	
	
		if (!is_object($obj)) {
			fclose($fp);
			return;
		}
	

		fputs($fp, "===================================\n");
		foreach ( $obj as $name => $value ) {
			fputs($fp, "$name = [");
			fputs($fp, $value);
			fputs($fp, "]\n");
			
			$fields[] = "[$name]"; 	
			$data[]	  = $value;	
		}
		fclose($fp);

		
		$_FIELDS  = implode(", ", $fields);
		$_VALUES  = implode("', '", $data);
		$SQL	 = " INSERT INTO $tablename ";
		$SQL	.= " ($_FIELDS) "; 
		$SQL	.= " VALUES ";
		$SQL	.= " ('$_VALUES') ";		
		
		return $SQL;	

	}


	
	static function SQL_UpdateFromArray($tablename, $data, $criteria) {
		if (!is_array($data)) return;
		while (list($name, $value) = each($data)) {
			$updates[] = "$name = '$value'";	 	
		}
		$_UPDATES = implode(", ", $updates);

		$SQL	 = "UPDATE $tablename ";
		$SQL	.= "SET ";
		$SQL 	.= $_UPDATES;
		
		if ($criteria) {
			$SQL .= " WHERE $criteria ";
		}
	
		return $SQL;
			
	}
	

	static function SQL_UpdateFromObject($tablename, $obj, $criteria) {
		if (!is_object($obj)) return;
		foreach ( $obj as $name => $value ) {
	 	$value = (is_object($value)) ? '' : $value;
			$val = "'$value'";
			if ($val=="'__DBNULL__'") {
				$val = "NULL";
			}			
			$updates[] = "[$name] = $val";		
		}		
		
		$_UPDATES = implode(", ", $updates);
		$SQL	 = "UPDATE $tablename ";
		$SQL	.= "SET ";
		$SQL 	.= $_UPDATES;
		
		if ($criteria) {
			$SQL .= " WHERE $criteria ";
		}
	
		return $SQL;
			
	}
	
	
	static function WriteToLog($conn, $tablelog, $tablelogpk1, $tablelogpk2, $table, $action, $diff, $username, $ip, $iplocal, $computername) {
		$descr = $diff['descr'];
		$lastvalue = $diff['lastvalue'];
		
		
		
		
		
	}
	
	
	static function GetDataDifference($conn, $table, $criteria, $obj) {
		if (!is_object($obj)) return;
		foreach ( $obj as $name => $value ) {
			$fields[] = $name; 	
			$data[]	  = $value;	
		}
		
		
		$_FIELDS  = implode(', ', $fields);
		$SQL = sprintf('SELECT %s FROM %s WHERE %s', $_FIELDS, $table, $criteria);
		$rs  = $conn->Execute($SQL);
		while (!$rs->EOF) {
			foreach ( $obj as $name => $value ) {
				$diff = (trim($rs->fields[$name])!=trim($value));
				if ($diff) {
					$DESCR[]	= "$name=$value";
					$LASTVALUE[]= "$name=".$rs->fields[$name];		
				}
			}
			$rs->MoveNext();
		}
		
		
		$DESCR     = is_array($DESCR)     ? implode(', ', $DESCR) : null;
		$LASTVALUE = is_array($LASTVALUE) ? implode(', ', $LASTVALUE) : null;
		return array('descr'=>$DESCR, 'lastvalue'=>$LASTVALUE);
	
	}


	static function BuildCriteria(&$SQL_CRITERIA, $criteria, $objname, $columnname, $criteriaformatter) {
		if ($criteria[$objname]->checked) {
			$value = $criteria[$objname]->value;
		
			if ($criteriaformatter=='refParser') {
				$_added_criteria = SQLUTIL::refParser($columnname, $value);
			} else {
				
				
				$posvardbfield = stripos($criteriaformatter, "{db_field}");
				$posvarvalue = stripos($criteriaformatter, "{criteria_value}");
				if ($posvardbfield===false && $posvarvalue===false) {
					$_added_criteria = sprintf($criteriaformatter, $columnname, $value);
				} else {
					$_added_criteria = str_replace(array('{db_field}', '{criteria_value}'), array($columnname, $value), $criteriaformatter);
				}
			}
			
			if ($SQL_CRITERIA) {
				$SQL_CRITERIA .= " AND ".$_added_criteria;
			} else {
				$SQL_CRITERIA  = $_added_criteria;
			}
			
			
	
		}
		
		return $_added_criteria;
	}	


	static function BuildCriteriaDate(&$SQL_CRITERIA, $criteria, $objnamestart, $objnamestart, $columnname) {
	
	}



	static function refParser($txtField, $arrText) {
		
		$arrText = explode("\n", $arrText);
		
		foreach($arrText as $txtTempLineSearchText) {
			//process the keywords
			$arrSearch = explode(";", $txtTempLineSearchText);
			while (list(, $arrSearchText) = each($arrSearch)) {
			   	//process concate the text
				$txtTempSearchText = trim($arrSearchText);
				$txtSearchCriteria = $txtTempSearchText.";".$txtSearchCriteria;						
			}		
		}
	
		//$txtField = "inventorymoving_id";
		$i = 0;	
		$arrSearch = explode(";", $txtSearchCriteria);
		foreach($arrSearch as $arrSearchText) {
			if ($i>0) {
				if ($arrSearchText!="") {
					$txtSearchCriteria = $txtSearchCriteria." OR ".$txtField." = '".$arrSearchText."' ";
				}
			} else {
				$txtSearchCriteria = $txtField."= '".$arrSearchText."' ";
			}
	
			$i++;
		}
	
		return "(" . $txtSearchCriteria . ")";
	}
	
	

	static function Normal($obj) {
		$str = is_object($obj) ? '' : trim($obj);
		return str_replace(array("'", '"'), array("",""), $str);
	}

	static function GetRsFromTableById($tablename, $primarykey, $id, &$conn) {
		$sql = "select * from $tablename where $primarykey='$id' ";
		$rs  = $conn->Execute($sql);
		return $rs;	
	}

	static function GetRsHeinvFromTableByKey($heinv_art, $heinv_mat, $heinv_col, $season_id, $region_id, &$conn) {
		switch ($region_id) {
			case '01800' :   //Feragamo, khusus ART nya saya
				$sql = "select * from master_heinv where heinv_art='$heinv_art' and region_id='$region_id' ";				
				break;	
			default :
				$sql = "select * from master_heinv where heinv_art='$heinv_art' and heinv_mat='$heinv_mat' and heinv_col='$heinv_col' and region_id='$region_id' ";
		}
		
		$rs  = $conn->Execute($sql);
		return $rs;	
	}

}

?>
