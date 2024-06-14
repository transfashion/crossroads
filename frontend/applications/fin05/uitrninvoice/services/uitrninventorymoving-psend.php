<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$__ID 		= $_POST['__ID'];


$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
unset($obj);
$obj->inventorymoving_ispostedsend = 1;
$obj->inventorymoving_sendby = $username;
$obj->inventorymoving_senddate = date("Y-m-d H:i:s");
$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
$conn->Execute($SQL);


unset($obj);
$obj->sent = 1;
$obj->message = 'data sudah dikirim';
$data = array($obj);


$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>