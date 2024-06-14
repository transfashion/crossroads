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
	

}



unset($errors);
unset($data);

$data = array();

$SQL = "

			SET NOCOUNT ON;

			DECLARE @dt as smalldatetime
			DECLARE @region_id as varchar(5)
			DECLARE @branch_id as varchar(7)

			
			
			SET @dt = getdate();
			SET @region_id = '$region_id'
			SET @branch_id = '$branch_id'
			
			
			EXEC inv05cron_GetMvSum 	
				@dt,
				@region_id,
				@branch_id				
			
			SET NOCOUNT OFF;
";
	
try {
	//$rs = $conn->Execute($SQL);
	$numerator = time();
	$filename = "$region_id-$branch_id-$numerator.txt";
	$file = dirname(__FILE__)."/../../../../updater/inv/$filename";
	$fp   = fopen($file, "w");

	$rs = $conn->Execute($SQL);
	while (!$rs->EOF) {
		$rows = array(
			$rs->fields['heinv_id'],
			$rs->fields['C01'],
			$rs->fields['C02'],
			$rs->fields['C03'],
			$rs->fields['C04'],
			$rs->fields['C05'],
			$rs->fields['C06'],
			$rs->fields['C07'],
			$rs->fields['C08'],
			$rs->fields['C09'],
			$rs->fields['C10'],
			$rs->fields['C11'],
			$rs->fields['C12'],
			$rs->fields['C13'],
			$rs->fields['C14'],
			$rs->fields['C15'],
			$rs->fields['C16'],
			$rs->fields['C17'],
			$rs->fields['C18'],
			$rs->fields['C19'],
			$rs->fields['C20'],
			$rs->fields['C21'],
			$rs->fields['C22'],
			$rs->fields['C23'],
			$rs->fields['C24'],
			$rs->fields['C25']
		);	
		
		$line = implode("|", $rows );
		fputs($fp, $line."\r\n");
		
		$rs->MoveNext();
	}
	
	fclose($fp);
	
	// Compress filennya
	$fc = new COM ("TFC.Engine");
	$fc->CompressFile($file, 0);	
	unlink($file);
	
	
	
	unset($obj);
	$obj->filename = $filename.".compress";
	$data[] = $obj;


} catch (Exception $e) {
	$errors = new WebResultErrorObject("0x00000001", $e->GetMessage()."\nTransaction rollingback");
}



$totalCount = count($data);
$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));


?>