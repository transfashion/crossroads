<?
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

	define('__SERVICE__', 1);
	

	require_once dirname(__FILE__).'/start.inc.php';

	$__SESSID = $_POST['__SESSID'] ? $_POST['__SESSID'] : $_GET['__SESSID'];
	$__USERNAME = $_POST['__USERNAME'] ? $_POST['__USERNAME'] : $_GET['__USERNAME'];
	
 
	if ($__SESSID) {
		session_id($__SESSID);
		session_start();

		// Developer version		
		if ($__SESSID=="1234567890") {
			$_SESSION['islogin'] = 1;
			$_SESSION['username'] = 'transdev';			
		}

		if ($__SESSID=="1234567890-dhewe") {
			$_SESSION['islogin'] = 1;
			$_SESSION['username'] = 'dhewe';			
		}		
	}
	
	$ns      = $_GET['ns'] ? $_GET['ns'] : "";
	$object  = $_GET['object'] ? $_GET['object'] : "";
	$act     = $_GET['act'] ? "-".$_GET['act'] : "";

	if ($object=='client') {
		/* boleh akses tanpa login */
		$clientname = $_POST['clientname'];
		
		

		if ($clientname == 'Transmahagaya POS Valentino PI' || $clientname == 'Transmahagaya POS' ) {
			$_SESSION['islogin'] = 1;
			$_SESSION['username'] = $__USERNAME;			
		}
	}


	if (!$_SESSION['islogin']) {
		$errors = new WebResultErrorObject("0x00000001", "Authorization Error!! You have not been log in\n[ns:$ns][obj:$object][act:$act]");
		$objResult = new WebResultObject("objResult");
		$objResult->success = false;
		$objResult->errors = $errors;
		die(stripslashes(json_encode($objResult)));	
	}
	
	
	
	if ($_SESSION['username'] != $__USERNAME) {
		$errors = new WebResultErrorObject("0x00000001", "Authorization Error!! Your current session is denied, username: $__USERNAME");
		$objResult = new WebResultObject("objResult");
		$objResult->success = false;
		$objResult->errors = $errors;
		die(stripslashes(json_encode($objResult)));	
	}	


	$ns      = $_GET['ns'] ? $_GET['ns'] : "";
	$object  = $_GET['object'] ? $_GET['object'] : "";
	$act     = $_GET['act'] ? "-".$_GET['act'] : "";

	if (!$ns) {
		$ExecutedServiceConf	   = "applications/$object".".inc.php";
		$ExecutedService		   = "applications/$object".$act.".php";
	} else {
		$ExecutedServiceConf	   = "applications/$ns/$object/services/$object".".inc.php";
		$ExecutedService		   = "applications/$ns/$object/services/$object".$act.".php";
	}   

	if (!is_file($ExecutedService)) {
		$errors = new WebResultErrorObject("0x00000001", "$ExecutedService not found!!");
		$objResult = new WebResultObject("objResult");
		$objResult->success = false;
		$objResult->errors = $errors;
		die(stripslashes(json_encode($objResult)));
	} else {
		require_once $ExecutedServiceConf;
		require_once $ExecutedService;
	}
?>
