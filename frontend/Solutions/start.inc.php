<?

#[database]#
$db[type] = 'ado_mssql';
$db[host] = '172.16.10.20';
$db[user] = 'sa';
$db[pass] = 'meg@tower';
$db[name] = 'E_FRM2_MGP';


#debug
$InstanceDirect = "Enabled";

require_once 'adodb/adodb-exceptions.inc.php';
require_once 'adodb/adodb.class.php';

$ADODB_FETCH_MODE = 2;

$conn = &ADONewConnection($db[type]);
$DSN  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db[host]."; DATABASE=".$db[name]."; UID=".$db[user]."; PWD=".$db[pass].";";



try {
	$conn->Connect($DSN);
} catch(Exception $e) {
	die($e->getMessage());
}


?>