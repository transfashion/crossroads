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

	$timestamp = time();
	CreateTempTablePOS($timestamp);
	

	$sql = "SELECT * FROM _UPDATEMETHOD_";
	$rs  = $sqliteconn->Execute($sql);
	while (!$rs->EOF) {
		$tablename = $rs->fields['tablename'];
		$keystring  = $rs->fields['keys'];
		$keys = explode(",",$keystring); 
		UpdateTable($fpLog, $tablename, $keys, $sqliteconn, $synsign_id, $timestamp);
		$rs->MoveNext();
	}

	fclose($fpLog);

	finallizeposdata($timestamp);
	
	DropTempTablePOS($timestamp);
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
function UpdateTable($fpLog, $tablename, $keys, $sqliteconn, $synsign_id, $timestamp) {
	global $conn, $_LOG_ENABLED;
	$sql = "SELECT * FROM $tablename";
	$rs  = $sqliteconn->Execute($sql);


	$table_temp = "#" . $tablename . "_" . $timestamp;
	

	while (!$rs->EOF) {
		$i++;
		$criteria = CreateCriteriaFromKeys($keys, &$rs);
		$sql = 	"SELECT * FROM $table_temp WHERE $criteria";	
		$rsCheck = $conn->Execute($sql);
		if ($rsCheck->recordCount()) {
			/* UPDATE */
			$SQL = CreateSQLUpdateFromRS($timestamp, $tablename, $rs, $sqliteconn, $criteria, $keys);	
		} else {
			/* INSERT BARU */
			$SQL = CreateSQLInsertFromRS($timestamp, $tablename, $rs, $sqliteconn, $criteria);	
		}
	
		try {

			$conn->Execute($SQL);
			if ($tablename=='transaksi_hepos') {
			
				$SQL = "UPDATE #transaksi_hepos_$timestamp SET syncode='$synsign_id', syndate=getdate() WHERE $criteria ";
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

function CreateSQLUpdateFromRS($timestamp, $tablename, $rs, $sqliteconn, $criteria, $keys) {
	unset($checksql);
	
	$table_temp = "#" . $tablename . "_" . $timestamp;
	
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
	
	//$SQL .= "/* --------------------- \r\n";
	//$SQL .= $checksql;
	//$SQL .= " ----------------------- */ \r\n\r\n";
	$SQL  = "UPDATE $table_temp \r\n";
	$SQL .= "SET \r\n";
	$SQL .= implode(", \r\n", $_lines);
	$SQL .= "\r\nWHERE \r\n";
	$SQL .= $criteria;
	

	//$fileoutput = dirname(__FILE__)."/client-x.log.txt";
	//$fpLog = fopen($fileoutput, "w");

	//fputs($fpLog, $SQL);
	//fclose($fpLog);


	return $SQL;
}

function CreateSQLInsertFromRS($timestamp, $tablename, $rs, $sqliteconn, $criteria) {
	unset($checksql);

	$table_temp = "#" . $tablename . "_" . $timestamp;
		
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



	

	//$SQL .= "/* --------------------- \r\n";
	//$SQL .= $checksql;
	//$SQL .= " ----------------------- */ \r\n\r\n";
	$SQL  = "INSERT INTO $table_temp ";	
	$SQL .= "(".implode(", ", $_fields).") ";
	$SQL .= "VALUES ";
	$SQL .= "(".implode(", ", $_values).") ";
		

	return $SQL;
}

function finallizeposdata($timestamp)
{
	global $conn;
		
	$temp_transaksi_hepos = "#transaksi_hepos_" . $timestamp;
	$temp_transaksi_heposdetil = "#transaksi_heposdetil_" . $timestamp;
	$temp_transaksi_hepospayment = "#transaksi_hepospayment_" . $timestamp;
	
	$i=0;
	$sql = "select bon_id, rowid from $temp_transaksi_hepos";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF)
	{
		$i++;
		$bon_id = $rs->fields['bon_id'];
		$rowid  = $rs->fields['rowid'];
		
		$sqlcek = "select bon_id, rowid from transaksi_hepos where bon_id='$bon_id'";
		$rsCek  = $conn->Execute($sqlcek);
		if ($rsCek->recordCount())
		{
			$existingrowid = $rsCek->fields['rowid'];
			if ($rowid != $existingrowid)
				throw new Exception("Data konflik, Bon id '$bon_id' sudah ada di server, dengan rowid yang berbeda!\r\niterasi: $i\r\n");
			
			$SQL = CreateSqlIdenticTableUpdate($temp_transaksi_hepos, "transaksi_hepos", "bon_id='$bon_id'");
			$conn->Execute($SQL);
			
		}
		else 
		{
			
			$SQL = CreateSqlIdenticTableCopy($temp_transaksi_hepos, "transaksi_hepos", "bon_id='$bon_id'");
			$conn->Execute($SQL);

			$SQL = CreateSqlIdenticTableCopy($temp_transaksi_heposdetil, "transaksi_heposdetil", "bon_id='$bon_id'");
			$conn->Execute($SQL);
				
			$SQL = CreateSqlIdenticTableCopy($temp_transaksi_hepospayment, "transaksi_hepospayment", "bon_id='$bon_id'");
			$conn->Execute($SQL);

		}
		
		$rs->MoveNext();
	}
	
}


function CreateSqlIdenticTableCopy($tablesource, $tabledestination, $criteria)
{
	global $conn;

	
	$sql = "select * from $tablesource";
	$rs  = $conn->Execute($sql);
	while (list($fieldname,$value)=each($rs->fields)) {
		$_field = "[$fieldname]";
		$_fields[] = $_field;		
	}
	
	
	if (is_array($_fields))
	{	
		$F       = implode(", ", $_fields);
		$sqlins  = "INSERT INTO $tabledestination ($F) \r\n";
		$sqlins .= "SELECT $F FROM $tablesource WHERE $criteria";	
	}
	else 
	{
		$sqlins  = "SELECT * FROM $tablesource WHERE $criteria";
	}

	/*
	$fileoutput = dirname(__FILE__)."/client-x.log.txt";
	$fpLog = fopen($fileoutput, "w");
	
	fputs($fpLog, $sqlins);
	fclose($fpLog);
	*/
	
	return $sqlins;
	
}

function CreateSqlIdenticTableUpdate($tablesource, $tabledestination, $criteria)
{
	global $conn;
	
	
	$sql = "select * from $tablesource";
	$rs  = $conn->Execute($sql);
	while (list($fieldname,$value)=each($rs->fields)) {
		$_field = "[$fieldname]";
		$_value = "(SELECT [$fieldname] FROM $tablesource WHERE $criteria)";
		$_updatefield = "$_field = $_value"; 
		
		$_updatefields[] = $_updatefield;
	}
	
	if (is_array($_updatefields))
	{
		$sqlupd  = "UPDATE $tabledestination \r\n";
		$sqlupd .= "SET \r\n";
		$sqlupd .= implode(",\r\n ", $_updatefields) . "\r\n";
		$sqlupd .= "WHERE \r\n";
		$sqlupd .= $criteria;
	}
	else
	{
		$sqlupd = "SELECT * FROM $tablesource WHERE $criteria";
	}
		
	return $sqlupd;
}


function CreateTempTablePOS($timestamp) {
	global $conn;

	$SQL_Create_transaksi_hepos = "
	CREATE TABLE #transaksi_hepos_$timestamp (
	[bon_id] [varchar](40) NOT NULL,
	[bon_idext] [varchar](50) NULL,
	[bon_event] [varchar](30) NULL,
	[bon_date] [smalldatetime] NULL,
	[bon_createby] [varchar](30) NOT NULL,
	[bon_createdate] [smalldatetime] NOT NULL DEFAULT (getdate()),
	[bon_modifyby] [varchar](30) NULL,
	[bon_modifydate] [smalldatetime] NULL,
	[bon_isvoid] [tinyint] NOT NULL DEFAULT ((0)),
	[bon_voidby] [varchar](30) NULL,
	[bon_voiddate] [smalldatetime] NULL,
	[bon_replacefromvoid] [varchar](40) NULL,
	[bon_msubtotal] [decimal](18, 0) NOT NULL  DEFAULT ((0)),
	[bon_msubtvoucher] [decimal](18, 0) NOT NULL  DEFAULT ((0)),
	[bon_msubtdiscadd] [decimal](18, 0) NOT NULL  DEFAULT ((0)),
	[bon_msubtredeem] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_msubtracttotal] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_msubtotaltobedisc] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_mdiscpaympercent] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_mdiscpayment] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_mtotal] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_mpayment] [decimal](18, 0) NOT NULL  DEFAULT ((0)),
	[bon_mrefund] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_msalegross] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_msaletax] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_msalenet] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bon_itemqty] [int] NOT NULL  DEFAULT ((0)),
	[bon_rowitem] [int] NOT NULL   DEFAULT ((0)),
	[bon_rowpayment] [int] NOT NULL   DEFAULT ((0)),
	[bon_npwp] [varchar](50) NULL,
	[bon_fakturpajak] [varchar](50) NULL,
	[bon_adddisc_authusername] [varchar](50) NULL,
	[bon_disctype] [varchar](30) NULL,
	[customer_id] [varchar](10) NOT NULL  DEFAULT ((0)),
	[customer_name] [varchar](30) NULL,
	[customer_telp] [varchar](30) NULL,
	[customer_npwp] [varchar](30) NULL,
	[customer_ageid] [varchar](30) NULL,
	[customer_agename] [varchar](30) NULL,
	[customer_genderid] [varchar](30) NULL,
	[customer_gendername] [varchar](30) NULL,
	[customer_nationalityid] [varchar](30) NULL,
	[customer_nationalityname] [varchar](30) NULL,
	[customer_typename] [varchar](30) NULL,
	[customer_passport] [varchar](50) NULL,
	[customer_disc] [int] NOT NULL   DEFAULT ((0)),
	[voucher01_id] [varchar](30) NULL,
	[voucher01_name] [varchar](30) NULL,
	[voucher01_codenum] [varchar](30) NULL,
	[voucher01_method] [varchar](30) NULL,
	[voucher01_type] [varchar](30) NULL,
	[voucher01_discp] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[salesperson_id] [varchar](10) NOT NULL   DEFAULT ((0)),
	[salesperson_name] [varchar](30) NULL,
	[pospayment_id] [varchar](10) NOT NULL,
	[pospayment_name] [varchar](30) NULL,
	[posedc_id] [varchar](10) NOT NULL   DEFAULT ((0)),
	[posedc_name] [varchar](30) NULL,
	[machine_id] [varchar](10) NOT NULL,
	[region_id] [varchar](5) NOT NULL,
	[branch_id] [varchar](7) NOT NULL,
	[syncode] [varchar](50) NULL,
	[syndate] [smalldatetime] NULL,
	[rowid] [varchar](50) NOT NULL   DEFAULT (newid())
	)
	";


	$SQL_Create_transaksi_heposdetil = "
	CREATE TABLE #transaksi_heposdetil_$timestamp (
	[bon_id] [varchar](40) NOT NULL,
	[bondetil_line] [int] NOT NULL,
	[bondetil_gro] [varchar](10) NULL,
	[bondetil_ctg] [varchar](10) NULL,
	[bondetil_art] [varchar](30) NULL,
	[bondetil_mat] [varchar](30) NULL,
	[bondetil_col] [varchar](30) NULL,
	[bondetil_size] [varchar](30) NULL,
	[bondetil_descr] [varchar](50) NULL,
	[bondetil_qty] [int] NULL,
	[bondetil_mpricegross] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bondetil_mdiscpstd01] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bondetil_mdiscrstd01] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bondetil_mpricenettstd01] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bondetil_mdiscpvou01] [decimal](18, 0) NOT NULL  DEFAULT ((0)),
	[bondetil_mdiscrvou01] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bondetil_mpricecettvou01] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bondetil_vou01id] [varchar](10) NULL,
	[bondetil_vou01codenum] [varchar](30) NULL,
	[bondetil_vou01type] [varchar](10) NULL,
	[bondetil_vou01method] [varchar](50) NULL,
	[bondetil_vou01discp] [decimal](18, 0) NOT NULL DEFAULT ((0)),
	[bondetil_mpricenett] [decimal](18, 0) NOT NULL   DEFAULT ((0)),
	[bondetil_msubtotal] [decimal](18, 0) NOT NULL  DEFAULT ((0)),
	[bondetil_rule] [varchar](2) NULL,
	[heinv_id] [varchar](13) NULL,
	[heinvitem_id] [varchar](13) NULL,
	[heinvitem_barcode] [varchar](30) NULL,
	[region_id] [varchar](5) NULL,
	[region_nameshort] [varchar](30) NULL,
	[colname] [varchar](5) NULL,
	[sizetag] [varchar](5) NULL,
	[proc] [varchar](5) NULL,
	[bon_idext] [varchar](50) NULL,
	[rowid] [varchar](50) NULL
	)
	";

	$SQL_Create_transaksi_hepospayment = "
	CREATE TABLE #transaksi_hepospayment_$timestamp (
	[bon_id] [varchar](40) NOT NULL,
	[payment_line] [int] NOT NULL,
	[payment_cardnumber] [varchar](40) NULL,
	[payment_cardholder] [varchar](40) NULL,
			[payment_mvalue] [decimal](18, 0) NULL,
			[payment_mcash] [decimal](18, 0) NULL,
			[payment_installment] [int] NULL,
			[pospayment_id] [varchar](10) NULL,
			[pospayment_name] [varchar](30) NULL,
			[pospayment_bank] [varchar](30) NULL,
			[posedc_id] [varchar](10) NULL,
			[posedc_name] [varchar](30) NULL,
			[bon_idext] [varchar](6) NULL,
			[rowid] [varchar](50) NULL ,
	)
			";

	$conn->Execute($SQL_Create_transaksi_hepos);
	$conn->Execute($SQL_Create_transaksi_heposdetil);
	$conn->Execute($SQL_Create_transaksi_hepospayment);

}

function DropTempTablePOS($timestamp) {
	global $conn;

	$SQL_Drop_transaksi_hepos        = "DROP TABLE #transaksi_hepos_$timestamp";
	$SQL_Drop_transaksi_heposdetil   = "DROP TABLE #transaksi_heposdetil_$timestamp";
	$SQL_Drop_transaksi_hepospayment = "DROP TABLE #transaksi_hepospayment_$timestamp";

	$conn->Execute($SQL_Drop_transaksi_hepos);
	$conn->Execute($SQL_Drop_transaksi_heposdetil);
	$conn->Execute($SQL_Drop_transaksi_hepospayment);

}



?>