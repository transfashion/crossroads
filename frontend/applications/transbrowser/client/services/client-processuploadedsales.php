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
	
	UseVoucherIfAny($timestamp);
	CreateVoucherIfAny($timestamp);
	DropTempTablePOS($timestamp);
	$conn->CommitTrans();

	unset($obj);
	$obj->test = "test";
	$data[] = $obj;




} catch (exception $e) {
	$errors = new WebResultErrorObject("0x00000001", $e->GetMessage()."\nTransaction rollingback");
	// $conn->RollbackTrans();
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
		
		// Update Data Customer
		UpdateDataCustomer($bon_id);
		
		// Update Extended DataDetil
		UpdateDatadetilExtended($bon_id);		
		
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
	[customer_id] [varchar](30) NOT NULL  DEFAULT ((0)),
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
	[rowid] [varchar](50) NOT NULL   DEFAULT (newid()),
	[site_id_From] varchar(20)
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
	[posedc_approval] [varchar](30) NULL,
	[bon_idext] [varchar](6) NULL,
	[rowid] [varchar](50) NULL ,
	)
			";

	$conn->Execute($SQL_Create_transaksi_hepos);
	$conn->Execute($SQL_Create_transaksi_heposdetil);
	$conn->Execute($SQL_Create_transaksi_hepospayment);

}


function UseVoucherIfAny($timestamp) {
	global $conn;

	try {
		$sql = "
			select 
				A.bon_id, 
				A.region_id, 
				(select region_name from master_region where region_id = A.region_id) as region_name,
				A.branch_id, 
				(select branch_name from master_branch where branch_id = A.branch_id) as branch_name,
				A.voucher01_codenum, 
				A.bon_msubtvoucher 
			from #transaksi_hepos_$timestamp A
		";

		$rs  = $conn->Execute($sql);
		$vouchers = array();
		while (!$rs->EOF) {
			$vouchers[] = array(
				'bon_id' => $rs->fields['bon_id'],
				'region_id' => $rs->fields['region_id'],
				'region_name' => $rs->fields['region_name'],
				'branch_id' => $rs->fields['branch_id'],
				'branch_name' => $rs->fields['branch_name'],
				'vou_id' => $rs->fields['voucher01_codenum'],
				'vou_value' => (float)$rs->fields['bon_msubtvoucher']
			);
			$rs->MoveNext();
		}

		$data = new stdClass;
		$data->vouchers = $vouchers;
		$postdata = json_encode($data);


		// Prepare new cURL resource
		$ch = curl_init("http://172.18.10.38:8086/use.php?");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		
		// Set HTTP Header for POST request
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postdata))
		);
		
		// Submit the POST request
		$respond = curl_exec($ch);
		
		// Close cURL session handle
		curl_close($ch);	

		$result = json_decode($respond);
		if ($result->code != 0) {
			throw new Exception($result->message);
		}

		$payload = $result->payload;

		if (!$payload->succes) {
			// throw new Exception('cannot update voucher usage');
			throw new Exception($respond);
		}

	} catch (Exception $ex) {
		throw $ex;
	}
}





function CreateVoucherIfAny($timestamp) {
	global $conn;

	try {
		
		$sql = "
			select 
			A.bon_id,
			A.region_id,
			(select region_name from master_region where region_id = A.region_id) as region_name,
			A.branch_id, 
			(select branch_name from master_branch where branch_id = A.branch_id) as branch_name,
			A.bon_event,
			A.customer_name,
			A.customer_telp,
			A.bon_mtotal,
			(select sum(bondetil_qty) from  #transaksi_heposdetil_$timestamp where bon_id=A.bon_id and bondetil_gro not like '%000000') as bon_qty
			from #transaksi_hepos_$timestamp A
		";


		$rs  = $conn->Execute($sql);
		$bons = array();
		while (!$rs->EOF) {
			$bons[] = array(
				'bon_id' => $rs->fields['bon_id'],
				'region_id' => $rs->fields['region_id'],
				'region_name' => $rs->fields['region_name'],
				'branch_id' => $rs->fields['branch_id'],
				'branch_name' => $rs->fields['branch_name'],
				'bon_event' => $rs->fields['bon_event'],
				'customer_name' => $rs->fields['customer_name'],
				'customer_telp' => $rs->fields['customer_telp'],
				'bon_mtotal' => (float)$rs->fields['bon_mtotal'],
				'bon_qty' => (float)$rs->fields['bon_qty']
			);
			$rs->MoveNext();
		}
		$postdata = json_encode($bons);


		// Prepare new cURL resource
		$ch = curl_init("http://172.18.10.38:8086/bongen.php?");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		
		// Set HTTP Header for POST request
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postdata))
		);
		
		// Submit the POST request
		$respond = curl_exec($ch);
		
		// Close cURL session handle
		curl_close($ch);	

		$result = json_decode($respond);
		if ($result->code != 0) {
			throw new Exception($result->message);
		}

		$payload = $result->payload;

		if (!$payload->succes) {
			// throw new Exception('cannot update voucher usage');
			throw new Exception($respond);
		}
		
	} catch (Exception $ex) {
		throw $ex;
	}
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


function UpdateDataCustomer($bon_id)
{
	global $conn;

	$sql = "SELECT * FROM transaksi_hepos WHERE bon_id='$bon_id'";
	
	$rs  = $conn->Execute($sql);
	

	$bon_customer_id   = trim($rs->fields['customer_id']);
	$bon_customer_name = trim($rs->fields['customer_name']);
	$bon_customer_telp = trim($rs->fields['customer_telp']);

	$customer_id   = trim($rs->fields['customer_id']);
	$customer_name = trim($rs->fields['customer_name']);
	$customer_telp = trim($rs->fields['customer_telp']);
	$customer_email = trim($rs->fields['customer_passport']);
	$gender_id     = trim($rs->fields['customer_genderid']);
	$region_id     = trim($rs->fields['region_id']);	
	$branch_id     = trim($rs->fields['branch_id']);
	$bon_date      = trim($rs->fields['bon_date']);

	

	// Cek dulu email yang diinput, apakah ada customer yang punya email ini ?
	$current_customer_found = false;
	if ($customer_email!="") {
		$sql = "SELECT * FROM master_customer WHERE customer_email='$customer_email'";
		$rsEm  = $conn->Execute($sql);
		if ($rsEm->recordCount()>0) {
			//echo " customer ketemu ";
			$customer_id = $rsEm->fields['customer_id'];
			$customer_telp = $rsEm->fields['customer_telp'];
			$current_customer_found = true;
		}
	}


	// Apabila CustomerId di bon tidak diisi
	if ($bon_customer_id=='')
	{
		// dan belum ada data customer yang ditemukan di atas, id customer yang digunakan adalah nomor telp
		if (!$current_customer_found) {
			//echo " update hepos by telp";
			$sql = "UPDATE transaksi_hepos SET customer_id='$customer_telp' WHERE bon_id='$bon_id'";
			$conn->Execute($sql);
			$customer_id = $customer_telp;
		} else {
			//echo " update hepos by Id";
			$sql = "UPDATE transaksi_hepos SET customer_id='$customer_id' WHERE bon_id='$bon_id'";
			$conn->Execute($sql);
		}
	}
	
	//echo "INFO-----";
	//echo "customer_id -> '$customer_id'";

	$sql = "SELECT * FROM master_customer WHERE customer_id='$customer_id' or customer_phone='$customer_id'";
	$rs  = $conn->Execute($sql);
	$customer_bon_creator = $rs->fields['bon_creator'];
	unset($obj);	
	if (!$rs->recordCount())
	{
		// Buat Data Customer Baru
		$obj->customer_id       = $customer_id;
		$obj->customer_namefull = $customer_name ? $customer_name  : $customer_id;
		$obj->customer_phone    = $customer_telp;
		$obj->customer_email    = $customer_email;
		$obj->customertype_id   = "C";
		$obj->gender_id         = $gender_id;
		$obj->region_id         = $region_id;
		$obj->branch_id         = $branch_id;
		$obj->date              = $bon_date;
		$obj->customer_createby = 'SYSTEM';
		$obj->customer_createdate = $bon_date;
		$obj->bon_creator = $bon_id;

		$SQL = SQLUTIL::SQL_InsertFromObject('master_customer', $obj);
		$conn->Execute($SQL);
	}
	else
	{
		
		// Customer Sudah Ada
		$existingdb_customer_name = $rs->fields['customer_namefull'];
		$existingdb_customer_phone = $rs->fields['customer_phone'];



		// Update customer email di bon, apabila email tidak diisi, tapi di master customer ada
		if ($customer_id!='01' && $customer_id!='') {
			$current_customer_email = $rs->fields['customer_email'];
			if ($current_customer_email!='') {
				$sql = "UPDATE transaksi_hepos SET customer_passport='$current_customer_email' WHERE bon_id='$bon_id'";
				$conn->Execute($sql);			
			}	
		}


		// Update Existing Customer
		if ($bon_id != $customer_bon_creator)
		{
			if ($bon_customer_telp != $existingdb_customer_phone)
			{
				$obj->customer_phone    = $bon_customer_telp;
			}
			
			if (trim($customer_email)!='')
			{
			 	$obj->customer_email = $customer_email;
			}

			if (trim($bon_customer_name)!='')
			{
				if (strlen($bon_customer_name)>strlen($existingdb_customer_name)) {
					$obj->customer_namefull = $bon_customer_name;
				}
			}
			
		 
			$obj->customer_modifyby = 'SYSTEM';
			$obj->customer_modifydate = $bon_date;
			$obj->date = $bon_date;
			$obj->bon_updator = $bon_id;
			
			$SQL = SQLUTIL::SQL_UpdateFromObject('master_customer', $obj, "customer_id='$customer_id' ");
			$conn->Execute($SQL);
		}		
	}

}


function UpdateDatadetilExtended($bon_id) {
	global $conn;
	
	$conn->Execute("DELETE FROM transaksi_heposdetilextd WHERE bon_id='$bon_id'");

	$sql = "
			select 
			bon_id,
			bondetil_line,
			heinv_id,
			heinvctg_class = (select heinvctg_class from master_heinvctg where heinvctg_id=A.heinvctg_id AND region_id=A.region_id),
			heinvctg_gender = (select heinvctg_gender from master_heinvctg where heinvctg_id=A.heinvctg_id AND region_id=A.region_id),
			season_id = (select season_id from master_heinv where heinv_id=A.heinv_id),
			qty = bondetil_qty,
			gross = itemgrossori,
			nett = nett
			 from view_hepos_bonlist_1 A
			where bon_id ='$bon_id'	
	";
	
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$bon_id = $rs->fields['bon_id'];
		$bondetil_line = $rs->fields['bondetil_line'];
		$heinv_id = $rs->fields['heinv_id'];
		$heinvctg_class = $rs->fields['heinvctg_class'];
		$heinvctg_gender = $rs->fields['heinvctg_gender'];
		$season_id = $rs->fields['season_id'];
		$qty = (int)$rs->fields['qty'];
		$gross = (float)$rs->fields['gross'];
		$nett = (float)$rs->fields['nett'];
	
		$sqlx = "
		INSERT INTO transaksi_heposdetilextd
		(bon_id, bondetil_line, heinv_id, heinvctg_class, heinvctg_gender, season_id, qty, gross, nett)
		VALUES
		('$bon_id', '$bondetil_line', '$heinv_id', '$heinvctg_class', '$heinvctg_gender', '$season_id', '$qty', '$gross', '$nett')
		";
		$conn->Execute($sqlx);
	
	
		$rs->MoveNext();
	}

}




?>
