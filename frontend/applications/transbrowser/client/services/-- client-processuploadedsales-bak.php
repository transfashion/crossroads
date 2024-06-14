<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];

$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}

	$region_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'region_id', '', "{criteria_value}");
	$branch_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'branch_id', '', "{criteria_value}");
	$machine_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'machine_id', '', "{criteria_value}");
	$client_date = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'client_date', '', "{criteria_value}");
	$synsign_type = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'synsign_type', '', "{criteria_value}");
	$synsign_id = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'synsign_id', '', "{criteria_value}");
	$cachefile = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'cachefile', '', "{criteria_value}");

}

unset($errors);

$_LOG_ENABLED = false;
/* cek apakah data sudah terupload */
try {

	//$cachefile = "SL.00200.0000600.20100630.634135178831718750.db";
	$datafile = dirname(__FILE__)."/../../../../../../data/sales/".$cachefile;
	if (!is_file($datafile)) throw new Exception("$datafile is not a file");


	$sqliteconn = &ADONewConnection('sqlite');
	$sqliteconn->Connect($datafile);

	$conn->BeginTrans();

	$fileoutput = dirname(__FILE__)."/client-processuploadedsales.log.txt";
	$fpLog = fopen($fileoutput, "w");
	if (!$fpLog)  throw new Exception("cannot write log file");


	$sql = "SELECT * FROM _UPDATEMETHOD_";
	$rs  = $sqliteconn->Execute($sql);
	while (!$rs->EOF) {
		$tablename = $rs->fields['tablename'];
		$keystring  = $rs->fields['keys'];
		$keys = explode(",",$keystring); 
		UpdateTable($fpLog, $tablename, $keys, $sqliteconn, $synsign_id);
		$rs->MoveNext();
	}

	fclose($fpLog);

	$conn->CommitTrans();

	unset($obj);
	$obj->test = "test";
	$data[] = $obj;

} catch (exception $e) {
	$errors = new WebResultErrorObject("0x00000001", $e->GetMessage()."\nTransaction rollingback");
	$conn->RollbackTrans();
}

$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = $errors ? false : true;
$objResult->data = $data;
$objResult->errors = $errors;
if (!$errors) unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));






//------------------------------------------------------------------------------end
function UpdateTable($fpLog, $tablename, $keys, $sqliteconn, $synsign_id) {
	global $conn, $_LOG_ENABLED;
	$sql = "SELECT * FROM $tablename";
	$rs  = $sqliteconn->Execute($sql);


	while (!$rs->EOF) {
		$i++;
		$criteria = CreateCriteriaFromKeys($keys, &$rs);
		$sql = 	"SELECT * FROM $tablename WHERE $criteria";	
		$rsCheck = $conn->Execute($sql);
		if ($rsCheck->recordCount()) {
			/* UPDATE */


			$SQL = CreateSQLUpdateFromRS($tablename, $rs, $sqliteconn, $criteria, $keys);	
		} else {
			/* INSERT BARU */
			$SQL = CreateSQLInsertFromRS($tablename, $rs, $sqliteconn, $criteria);	
		}
	
		try {

			$conn->Execute($SQL);
			if ($tablename=='transaksi_hepos') {
			
			
				$SQL = "UPDATE transaksi_hepos SET syncode='$synsign_id', syndate=getdate() WHERE $criteria ";
				$conn->Execute($SQL);
				
				if ($_LOG_ENABLED) {
					ob_start();
					var_dump($rs);
					$cnts = ob_get_contents();
					ob_end_clean();
					fputs($fpLog, "iteration: $i\r\n");
					fputs($fpLog, "rs: \r\n".$cnts."\r\n\r\n");
					fputs($fpLog, "sql: $SQL\r\n\r\n");
					fputs($fpLog, "------------------------------------------------------------------------------------\r\n\r\n\r\n");
				}
			}
						
		} catch (exception $e) {
			throw new Exception($e->GetMessage());	
		}
		
		$rs->MoveNext();
	}
	
}


function CreateCriteriaFromKeys($keys, &$rs) {
	$line = "";
	unset($lines);
	foreach ($keys as $key) {
		$keyfield = trim($key);
		$value = $rs->fields["$keyfield"];
		$line = "$keyfield = '$value'";
		$lines[] = $line;
	}
	return implode(" AND ", $lines);
}


function CreateSQLUpdateFromRS($tablename, $rs, $sqliteconn, $criteria, $keys) {
	unset($checksql);
	while (list($fieldname,$value)=each($rs->fields)) {
	
		$jump = false;
		foreach ($keys as $key) {
			$keyfield = trim($key);
			if ($fieldname==$keyfield) {
				$jump = true;
				break;
			}
		}	
	
		if (!$jump) {
			$sql = "SELECT case when $fieldname is not null then 0 else 1 end AS valueisnull, $fieldname FROM $tablename WHERE $criteria";
			$rsI = $sqliteconn->Execute($sql);
			if ($rsI->fields['valueisnull']) {
				$_line = "  [$fieldname]=NULL";
				$_lines[] = $_line;
			} else {
				$_line = "  [$fieldname]='".$value."'";
				$_lines[] = $_line;		
			}
			
			$checksql .= "$sql -> result: ".$rsI->fields['valueisnull'].", value: ".$rsI->fields[$fieldname]."\r\n";
		}
	}
	
	$SQL .= "/* --------------------- \r\n";
	$SQL .= $checksql;
	$SQL .= " ----------------------- */ \r\n\r\n";
	$SQL .= "UPDATE $tablename \r\n";
	$SQL .= "SET \r\n";
	$SQL .= implode(", \r\n", $_lines);
	$SQL .= "\r\nWHERE \r\n";
	$SQL .= $criteria;
	



	$fileoutput = dirname(__FILE__)."/client-x.log.txt";
	$fpLog = fopen($fileoutput, "w");

	fputs($fpLog, $SQL);
	fclose($fpLog);





	return $SQL;
}

function CreateSQLInsertFromRS($tablename, $rs, $sqliteconn, $criteria) {
	unset($checksql);
	while (list($fieldname,$value)=each($rs->fields)) {
		$_field = "[$fieldname]";
		$_fields[] = $_field;
	
		$sql = "SELECT case when $fieldname is not null then 0 else 1 end AS valueisnull FROM $tablename  WHERE $criteria";
		$rsI = $sqliteconn->Execute($sql);
		if ($rsI->fields['valueisnull']) {
			$_value = "NULL";
			$_values[] = $_value;
		} else {
			$_value = "'".$rs->fields[$fieldname]."'";
			$_values[] = $_value;	
		}	

		$checksql .= "$sql -> result: ".$rsI->fields['valueisnull'].", value: ".$rsI->fields[$fieldname]."\r\n";
	}



	

	$SQL .= "/* --------------------- \r\n";
	$SQL .= $checksql;
	$SQL .= " ----------------------- */ \r\n\r\n";
	$SQL  = "INSERT INTO $tablename ";	
	$SQL .= "(".implode(", ", $_fields).") ";
	$SQL .= "VALUES ";
	$SQL .= "(".implode(", ", $_values).") ";
		

	return $SQL;
}

?>