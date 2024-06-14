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

	$fileoutput = dirname(__FILE__)."/client-processuploadedsales.log.txt";
	$fpLog = fopen($fileoutput, "w");

	$SQL_filelog = "sqllog.sl.".$region_id.".".date("Ymd").".txt";
	$filelog = dirname(__FILE__)."/../../../../../../data/log/".$SQL_filelog;
	$fpSQLLog = fopen($filelog, "a");	

	$Err_filelog = "err.sl.".$region_id.".".date("Ymd").".txt";
	$file_err_log = dirname(__FILE__)."/../../../../../../data/error/".$Err_filelog;
	$fpErrLog = fopen($file_err_log, "a");	


	$timestamp = $region_id."_".$branch_id."_".time();

	//$cachefile = "SL.00200.0000600.20100630.634135178831718750.db";
	$datafile = dirname(__FILE__)."/../../../../../../data/sales/".$cachefile;
	if (!is_file($datafile)) throw new Exception("$datafile is not a file");


	$sqliteconn = &ADONewConnection('sqlite');
	$sqliteconn->Connect($datafile);

	$conn->BeginTrans();

	CreateTempTablePOS($timestamp, $fpSQLLog);


	//* MASUKKAN KE TABLE TEMPORARY *//
	$sql = "SELECT * FROM _UPDATEMETHOD_";
	$rs  = $sqliteconn->Execute($sql);
	while (!$rs->EOF) {
		$tablename_temp = "#temp_".$rs->fields['tablename']."_".$timestamp;
		$tablename = $rs->fields['tablename'];
		$keystring  = $rs->fields['keys'];
		$keys = explode(",",$keystring); 
		UpdateTable($fpSQLLog, $fpLog, $tablename_temp, $tablename, $keys, $sqliteconn, $synsign_id);
		$rs->MoveNext();
	}


	/* Baca dari table #temporary, Masukkan ke Table Real */
	$sql = "/* 74 */ SELECT * FROM #temp_transaksi_hepos_$timestamp ";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$bon_id = $rs->fields['bon_id'];
		$rowid  = $rs->fields['rowid'];
		$sqltest = "SELECT * FROM transaksi_hepos WHERE bon_id = '$bon_id' ";
		$rsTest  = $conn->Execute($sqltest);

		if ($rsTest->recordCount()) {
			$rowidtest = $rsTest->fields['rowid'];
			if ($rowid!=$rowidtest) {
				throw new Exception("bon '$bon_id' sudah terdapat di database dengan signature yang berbeda");			
			} else {
				UpdateMaster($bon_id, "#temp_transaksi_hepos_$timestamp", "transaksi_hepos", $fpErrLog);
				UpdateDetil($bon_id, "#temp_transaksi_heposdetil_$timestamp", "transaksi_heposdetil", $fpErrLog);
				UpdatePayment($bon_id, "#temp_transaksi_hepospayment_$timestamp", "transaksi_hepospayment", $fpErrLog);
			}
		} else {
			/* langsung insert ke db */
				UpdateMaster($bon_id, "#temp_transaksi_hepos_$timestamp", "transaksi_hepos", $fpErrLog);
				UpdateDetil($bon_id, "#temp_transaksi_heposdetil_$timestamp", "transaksi_heposdetil", $fpErrLog);
				UpdatePayment($bon_id, "#temp_transaksi_hepospayment_$timestamp", "transaksi_hepospayment", $fpErrLog);			
		}
		
		$rs->MoveNext();	
	}
	



	DropTempTablePOS($timestamp, $fpSQLLog);

	$conn->CommitTrans();
	fclose($fpLog);
	fclose($fpSQLLog);
	fclose($fpErrLog);
	
	
	unlink($file_err_log);

	unset($obj);
	$obj->test = "test";
	$data[] = $obj;

} catch (exception $e) {
	$conn->RollbackTrans();
	$errors = new WebResultErrorObject("0x00000001", $e->GetMessage()."\nTransaction rollingback");
}

$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = $errors ? false : true;
$objResult->data = $data;
$objResult->errors = $errors;
if (!$errors) unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));






//------------------------------------------------------------------------------end
function UpdateTable($fpSQLLog, $fpLog, $tablename_temp, $tablename, $keys, $sqliteconn, $synsign_id) {
	global $conn, $_LOG_ENABLED, $region_id;
	$sql = "SELECT * FROM $tablename";
	$rs  = $sqliteconn->Execute($sql);



	while (!$rs->EOF) {
		$i++;
		$criteria = CreateCriteriaFromKeys($keys, &$rs);
		$sql = 	"/* 141 */ SELECT * FROM $tablename_temp WHERE $criteria";	
		$rsCheck = $conn->Execute($sql);
		if ($rsCheck->recordCount()) {
			/* UPDATE */
			$SQL = CreateSQLUpdateFromRS($tablename_temp, $tablename, $rs, $sqliteconn, $criteria, $keys);	
		} else {
			/* INSERT BARU */
			$SQL = CreateSQLInsertFromRS($tablename_temp, $tablename, $rs, $sqliteconn, $criteria);	
		}
	
		try {

			/* tulis ke log */
			fputs($fpSQLLog, $SQL."\r\n\r\n");

			$conn->Execute($SQL);
			if ($tablename=='transaksi_hepos') {
				$SQL = "UPDATE $tablename_temp SET syncode='$synsign_id', syndate=getdate() WHERE $criteria ";
				fputs($fpSQLLog, $SQL."\r\n\r\n");
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


function CreateSQLUpdateFromRS($tablename_temp, $tablename, $rs, $sqliteconn, $criteria, $keys) {
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
	
	//$SQL .= "/* --------------------- \r\n";
	//$SQL .= $checksql;
	//$SQL .= " ----------------------- */ \r\n\r\n";
	$SQL .= "UPDATE $tablename_temp \r\n";
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

function CreateSQLInsertFromRS($tablename_temp, $tablename, $rs, $sqliteconn, $criteria) {
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



	//$SQL .= "/* --------------------- \r\n";
	//$SQL .= $checksql;
	//$SQL .= " ----------------------- */ \r\n\r\n";
	$SQL  = "INSERT INTO $tablename_temp ";	
	$SQL .= "(".implode(", ", $_fields).") ";
	$SQL .= "VALUES ";
	$SQL .= "(".implode(", ", $_values).") ";
		

	$fileoutput = dirname(__FILE__)."/client-x.log.txt";
	$fpLog = fopen($fileoutput, "w");

	fputs($fpLog, $SQL);
	fclose($fpLog);
	

	return $SQL;
}




function CreateTempTablePOS($timestamp, $fpSQLLog) {
	global $conn;
	
	$SQL_Create_transaksi_hepos = "
		CREATE TABLE #temp_transaksi_hepos_$timestamp (
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
		CREATE TABLE #temp_transaksi_heposdetil_$timestamp (
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
		CREATE TABLE #temp_transaksi_hepospayment_$timestamp (
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

	fputs($fpSQLLog, $SQL_Create_transaksi_hepos."\r\n\r\n");
	fputs($fpSQLLog, $SQL_Create_transaksi_heposdetil."\r\n\r\n");
	fputs($fpSQLLog, $SQL_Create_transaksi_hepospayment."\r\n\r\n");

	$conn->Execute($SQL_Create_transaksi_hepos);
	$conn->Execute($SQL_Create_transaksi_heposdetil);
	$conn->Execute($SQL_Create_transaksi_hepospayment);

}




function DropTempTablePOS($timestamp, $fpSQLLog) {
	global $conn;

	$SQL_Drop_transaksi_hepos        = "DROP TABLE #temp_transaksi_hepos_$timestamp";
	$SQL_Drop_transaksi_heposdetil   = "DROP TABLE #temp_transaksi_heposdetil_$timestamp";
	$SQL_Drop_transaksi_hepospayment = "DROP TABLE #temp_transaksi_hepospayment_$timestamp";

	fputs($fpSQLLog, $SQL_Drop_transaksi_hepos."\r\n\r\n");
	fputs($fpSQLLog, $SQL_Drop_transaksi_heposdetil."\r\n\r\n");
	fputs($fpSQLLog, $SQL_Drop_transaksi_hepospayment."\r\n\r\n");


	$conn->Execute($SQL_Drop_transaksi_hepos);
	$conn->Execute($SQL_Drop_transaksi_heposdetil);
	$conn->Execute($SQL_Drop_transaksi_hepospayment);

}



function UpdateMaster($bon_id, $tablename_temp, $tablename, $fpErrLog) {
	global $conn;

	$SQL = "SELECT * FROM $tablename_temp WHERE bon_id='$bon_id'";
	$rs  = $conn->Execute($SQL);	

	unset($obj);
	$obj->bon_idext = $rs->fields['bon_idext'];
	$obj->bon_event = $rs->fields['bon_event'];
	$obj->bon_date = $rs->fields['bon_date'];
	$obj->bon_createby = $rs->fields['bon_createby'];
	$obj->bon_createdate = $rs->fields['bon_createdate'];
	$obj->bon_modifyby = $rs->fields['bon_modifyby'];
	$obj->bon_modifydate = $rs->fields['bon_modifydate'];
	$obj->bon_isvoid = $rs->fields['bon_isvoid'];
	$obj->bon_voidby = $rs->fields['bon_voidby'];
	$obj->bon_voiddate = $rs->fields['bon_voiddate'];
	$obj->bon_replacefromvoid = $rs->fields['bon_replacefromvoid'];
	$obj->bon_msubtotal = (float) $rs->fields['bon_msubtotal'];
	$obj->bon_msubtvoucher = (float) $rs->fields['bon_msubtvoucher'];
	$obj->bon_msubtdiscadd = (float) $rs->fields['bon_msubtdiscadd'];
	$obj->bon_msubtredeem = (float) $rs->fields['bon_msubtredeem'];
	$obj->bon_msubtracttotal = (float) $rs->fields['bon_msubtracttotal'];
	$obj->bon_msubtotaltobedisc = (float) $rs->fields['bon_msubtotaltobedisc'];
	$obj->bon_mdiscpaympercent = (float) $rs->fields['bon_mdiscpaympercent'];
	$obj->bon_mdiscpayment = (float) $rs->fields['bon_mdiscpayment'];
	$obj->bon_mtotal = (float) $rs->fields['bon_mtotal'];
	$obj->bon_mpayment = (float) $rs->fields['bon_mpayment'];
	$obj->bon_mrefund = (float) $rs->fields['bon_mrefund'];
	$obj->bon_msalegross = (float) $rs->fields['bon_msalegross'];
	$obj->bon_msaletax = (float) $rs->fields['bon_msaletax'];
	$obj->bon_msalenet = (float) $rs->fields['bon_msalenet'];
	$obj->bon_itemqty = (int) $rs->fields['bon_itemqty'];
	$obj->bon_rowitem = (int) $rs->fields['bon_rowitem'];
	$obj->bon_rowpayment = (int) $rs->fields['bon_rowpayment'];
	$obj->bon_npwp = $rs->fields['bon_npwp'];
	$obj->bon_fakturpajak = $rs->fields['bon_fakturpajak'];
	$obj->bon_adddisc_authusername = $rs->fields['bon_adddisc_authusername'];
	$obj->bon_disctype = $rs->fields['bon_disctype'];
	$obj->customer_id = $rs->fields['customer_id'];
	$obj->customer_name = $rs->fields['customer_name'];
	$obj->customer_telp = $rs->fields['customer_telp'];
	$obj->customer_npwp = $rs->fields['customer_npwp'];
	$obj->customer_ageid = $rs->fields['customer_ageid'];
	$obj->customer_agename = $rs->fields['customer_agename'];
	$obj->customer_genderid = $rs->fields['customer_genderid'];
	$obj->customer_gendername = $rs->fields['customer_gendername'];
	$obj->customer_nationalityid = $rs->fields['customer_nationalityid'];
	$obj->customer_nationalityname = $rs->fields['customer_nationalityname'];
	$obj->customer_typename = $rs->fields['customer_typename'];
	$obj->customer_passport = $rs->fields['customer_passport'];
	$obj->customer_disc = $rs->fields['customer_disc'];
	$obj->voucher01_id = $rs->fields['voucher01_id'];
	$obj->voucher01_name = $rs->fields['voucher01_name'];
	$obj->voucher01_codenum = $rs->fields['voucher01_codenum'];
	$obj->voucher01_method = $rs->fields['voucher01_method'];
	$obj->voucher01_type = $rs->fields['voucher01_type'];
	$obj->voucher01_discp = (float) $rs->fields['voucher01_discp'];
	$obj->salesperson_id = $rs->fields['salesperson_id'];
	$obj->salesperson_name = $rs->fields['salesperson_name'];
	$obj->pospayment_id = $rs->fields['pospayment_id'];
	$obj->pospayment_name = $rs->fields['pospayment_name'];
	$obj->posedc_id = $rs->fields['posedc_id'];
	$obj->posedc_name = $rs->fields['posedc_name'];
	$obj->machine_id = $rs->fields['machine_id'];
	$obj->region_id = $rs->fields['region_id'];
	$obj->branch_id = $rs->fields['branch_id'];
	$obj->syncode = $rs->fields['syncode'];
	$obj->syndate = $rs->fields['syndate'];
	$obj->rowid = $rs->fields['rowid'];
	
	// cek data di table real, update, insert
	$sql = "select * from $tablename where bon_id='$bon_id' ";
	$rsC  = $conn->Execute($sql);
	if ($rsC->recordCount()) {
		$SQL = SQLUTIL::SQL_UpdateFromObject($tablename, $obj, "bon_id='$bon_id'");
	} else {
		$obj->bon_id = $rs->fields['bon_id'];
		$SQL = SQLUTIL::SQL_InsertFromObject($tablename, $obj);
	}
		
	//fputs($fpErrLog, json_encode($obj)."\r\n");
	$conn->Execute($SQL);

}


function UpdateDetil($bon_id, $tablename_temp, $tablename, $fpErrLog) {
	global $conn;

	$SQL = "SELECT * FROM $tablename_temp WHERE bon_id='$bon_id'";
	$rs  = $conn->Execute($SQL);	

	while (!$rs->EOF) {
		unset($obj);
		$bondetil_line = $rs->fields['bondetil_line'];
		
		$obj->bondetil_gro = $rs->fields['bondetil_gro'];
		$obj->bondetil_ctg = $rs->fields['bondetil_ctg'];
		$obj->bondetil_art = $rs->fields['bondetil_art'];
		$obj->bondetil_mat = $rs->fields['bondetil_mat'];
		$obj->bondetil_col = $rs->fields['bondetil_col'];
		$obj->bondetil_size = $rs->fields['bondetil_size'];
		$obj->bondetil_descr = $rs->fields['bondetil_descr'];
		$obj->bondetil_qty = (int) $rs->fields['bondetil_qty'];
		$obj->bondetil_mpricegross = (float) $rs->fields['bondetil_mpricegross'];
		$obj->bondetil_mdiscpstd01 = (float) $rs->fields['bondetil_mdiscpstd01'];
		$obj->bondetil_mdiscrstd01 = (float) $rs->fields['bondetil_mdiscrstd01'];
		$obj->bondetil_mpricenettstd01 = (float) $rs->fields['bondetil_mpricenettstd01'];
		$obj->bondetil_mdiscpvou01 = (float) $rs->fields['bondetil_mdiscpvou01'];
		$obj->bondetil_mdiscrvou01 = (float) $rs->fields['bondetil_mdiscrvou01'];
		$obj->bondetil_mpricecettvou01 = (float) $rs->fields['bondetil_mpricecettvou01'];
		$obj->bondetil_vou01id = $rs->fields['bondetil_vou01id'];
		$obj->bondetil_vou01codenum = $rs->fields['bondetil_vou01codenum'];
		$obj->bondetil_vou01type = $rs->fields['bondetil_vou01type'];
		$obj->bondetil_vou01method = $rs->fields['bondetil_vou01method'];
		$obj->bondetil_vou01discp = (float) $rs->fields['bondetil_vou01discp'];
		$obj->bondetil_mpricenett = (float) $rs->fields['bondetil_mpricenett'];
		$obj->bondetil_msubtotal = (float) $rs->fields['bondetil_msubtotal'];
		$obj->bondetil_rule = $rs->fields['bondetil_rule'];
		$obj->heinv_id = $rs->fields['heinv_id'];
		$obj->heinvitem_id = $rs->fields['heinvitem_id'];
		$obj->heinvitem_barcode = $rs->fields['heinvitem_barcode'];
		$obj->region_id = $rs->fields['region_id'];
		$obj->region_nameshort = $rs->fields['region_nameshort'];
		$obj->colname = $rs->fields['colname'];
		$obj->sizetag = $rs->fields['sizetag'];
		$obj->proc = $rs->fields['proc'];
		$obj->bon_idext = $rs->fields['bon_idext'];
		$obj->rowid = $rs->fields['rowid'];


		/* ambil price ori dari $obj->heinv_id = $rs->fields['heinv_id']; */
		$heinv_id = $rs->fields['heinv_id'];
		$sql = "SELECT heinv_priceori FROM master_heinv WHERE heinv_id='$heinv_id'";
		$rsI = $conn->Execute($sql);
		$heinv_priceori = (float) $rsI->fields['heinv_priceori'];
		if ($heinv_priceori==0 || $heinv_priceori  <  $obj->bondetil_mpricegross) {
			$heinv_priceori = $obj->bondetil_mpricegross;	
		} 
		$obj->bondetil_mpriceori = $heinv_priceori;



		// cek data di table real, update, insert
		$sql = "select * from $tablename where bon_id='$bon_id' AND bondetil_line='$bondetil_line'";
		$rsC  = $conn->Execute($sql);
		if ($rsC->recordCount()) {
			$SQL = SQLUTIL::SQL_UpdateFromObject($tablename, $obj, "bon_id='$bon_id' AND bondetil_line='$bondetil_line'");
		} else {
			$obj->bon_id = $rs->fields['bon_id'];
			$obj->bondetil_line = $rs->fields['bondetil_line'];
			$SQL = SQLUTIL::SQL_InsertFromObject($tablename, $obj);
		}		
	
		$conn->Execute($SQL);
		$rs->MoveNext();
	}	
	
	
}


function UpdatePayment($bon_id, $tablename_temp, $tablename, $fpErrLog) {
	global $conn;

	$SQL = "SELECT * FROM $tablename_temp WHERE bon_id='$bon_id'";
	$rs  = $conn->Execute($SQL);	

	while (!$rs->EOF) {
		unset($obj);
		$payment_line = $rs->fields['payment_line'];
		
		$obj->payment_cardnumber = $rs->fields['payment_cardnumber'];
		$obj->payment_cardholder = $rs->fields['payment_cardholder'];
		$obj->payment_mvalue = (float) $rs->fields['payment_mvalue'];
		$obj->payment_mcash = (float) $rs->fields['payment_mcash'];
		$obj->payment_installment = $rs->fields['payment_installment'];
		$obj->pospayment_id = $rs->fields['pospayment_id'];
		$obj->pospayment_name = $rs->fields['pospayment_name'];
		$obj->pospayment_bank = $rs->fields['pospayment_bank'];
		$obj->posedc_id = $rs->fields['posedc_id'];
		$obj->posedc_name = $rs->fields['posedc_name'];
		$obj->bon_idext = $rs->fields['bon_idext'];
		$obj->rowid = $rs->fields['rowid'];
		
		// cek data di table real, update, insert
		$sql = "select * from $tablename where bon_id='$bon_id' AND payment_line='$payment_line'";
		$rsC  = $conn->Execute($sql);
		if ($rsC->recordCount()) {
			$SQL = SQLUTIL::SQL_UpdateFromObject($tablename, $obj, "bon_id='$bon_id' AND payment_line='$payment_line'");
		} else {
			$obj->bon_id = $rs->fields['bon_id'];
			$obj->payment_line = $rs->fields['payment_line'];		
			$SQL = SQLUTIL::SQL_InsertFromObject($tablename, $obj);
		}		
	
		$conn->Execute($SQL);
		$rs->MoveNext();
	}	

	
}




?>