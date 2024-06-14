<?
if (!defined('__SERVICE__')) {
	die("access denied");
}


	$username = $_SESSION['username'];
	$username2 = $_POST['username'];
	$password = $_POST['password'];


	$md5 = new COM ("IST.DataHash.MD5"); 
	$newPassword = $md5->Encrypt($password);


	$sql = "update master_user set user_password='$newPassword' where username='$username2' ";
	$conn->Execute($sql);
	
	

	//print "test";
	$obj->success = 1;
	$obj->message = "";
	//$obj->message = "password has been changed to $newPassword";
	




$data[0] = $obj;     
     
$objResult = new WebResultObject("objResult");
$objResult->totalCount = count($data);
$objResult->success = true;
$objResult->data = $data;
		
print(stripslashes(json_encode($objResult)));
?>