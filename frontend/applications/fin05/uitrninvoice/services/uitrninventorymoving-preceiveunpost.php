<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$__ID 		= $_POST['__ID'];


$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
unset($obj);
$obj->inventorymoving_ispostedreceive = 0;
$obj->inventorymoving_receiveby = '__DBNULL__';
$obj->inventorymoving_receivedate = '__DBNULL__';
$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
$conn->Execute($SQL);


unset($obj);
$obj->receivedunpost = 1;
$obj->message = 'data sudah dikembalikan';
$data = array($obj);


$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>