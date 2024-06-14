<?
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

require_once dirname(__FILE__).'/start.inc.php';

$username = $_POST['username'];
$password = $_POST['password'];


$sql = "SELECT  * FROM master_user WHERE username='$username' AND user_isdisabled=0";
$rs  = $conn->Execute($sql);


$objResult = new WebResultObject("objResult");
if ($rs->fields['username']) {

	/* Setting Parameter */
	$CHANNEL = $rs->fields['user_default_channel_id'] ? $rs->fields['user_default_channel_id'] : 0;
	$CHANNEL_CANBE_CHANGED = $rs->fields['user_can_change_channel'] ? $rs->fields['user_can_change_channel'] : 0;
	$CHANNEL_CANBE_BROWSED = $rs->fields['user_can_browse_channel'] ? $rs->fields['user_can_browse_channel'] : 0;

	$obj->username 			= $rs->fields['username'];
	$obj->user_fullname 	= $rs->fields['user_fullname'];
	$obj->user_password		= $rs->fields['user_password'];
	$obj->user_md5password  = $rs->fields['user_md5password'];
	$obj->parameter			= sprintf("CHANNEL=%s;CHANNEL_CANBE_CHANGED=%s;CHANNEL_CANBE_BROWSED=%s", $CHANNEL, $CHANNEL_CANBE_CHANGED, $CHANNEL_CANBE_BROWSED);
	
	
	/* cek password */
	// $md5 = new COM ("IST.DataHash.MD5"); 
	

	//if ($md5->Verify($password, $obj->user_password)) {
	//if ($obj->username=='dhewe') {
	if (md5($password)==$obj->user_md5password) {

	
		session_start();
		$_SESSION['islogin']    	= 1;
		$_SESSION['username']   	= $_POST['username'];
		$_SESSION['user_fullname']  = $obj->user_fullname;
		$obj->session_id 			= session_id(); 
		$obj->database				= $db[name]."@".addslashes($db[host]);
	

		//update user_md5password
		//$sql = "UPDATE master_user SET user_md5password='".md5($password)."' WHERE username='". $_SESSION['username'] ."' ";
		//$conn->Execute($sql);


		$objResult->success = true;
		$objResult->totalCount  = 1;
		$objResult->data[0] = $obj;
		$objResult->errors	= null;
	} else {
		$errors = new WebResultErrorObject("0x00000001", "Incorect password: $password for username: $username!!.");
		$objResult->success = false;
		$objResult->errors = $errors;
	}

} else {
	$errors = new WebResultErrorObject("0x00000001", "User $username not found!!");
	$objResult->success = false;
	$objResult->errors = $errors;
}



print (stripslashes(json_encode($objResult)));



?>
