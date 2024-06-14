<?
date_default_timezone_set('Asia/Jakarta');

require_once dirname(__FILE__).'/../../crossroads.config.php';
require_once dirname(__FILE__).'/sqlutil.inc.php';
require_once dirname(__FILE__).'/webresult.inc.php';
require_once dirname(__FILE__).'/adodb/adodb-exceptions.inc.php';
require_once dirname(__FILE__).'/adodb/adodb.class.php';




$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection($db[type]);


try {
//	$DSN  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db[host]."; DATABASE=".$db[name]."; UID=".$db[user]."; PWD=".$db[pass].";";
	$DSN  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db[host]."; DATABASE=".$db[name]."; UID=".$db[user]."; PWD=".$db[pass]."; Connect Timeout=300000";
	$conn->Connect($DSN);
} catch (exception $e) { 		
	$errors = new WebResultErrorObject("0x00000001", $e->GetMessage());
	$objResult = new WebResultObject("objResult");
	$objResult->success = false;
	$objResult->errors = $errors;
	die(stripslashes(json_encode($objResult)));	
} 

class Object {}
set_time_limit(4000000);
?>