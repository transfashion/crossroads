<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

//masukkan code smarty template
try {
	require_once dirname(__FILE__).'/../../../../smarty/Smarty.class.php';
	require_once dirname(__FILE__).'/../../../../smarty/smarty_framework.class.php';
} catch(Exception $e) {
	die($e->getMessage());
}

$objPage = new Smarty_Framework();	




$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];

$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria */
	
	$id = trim(SQLUTIL::BuildCriteria(&$PARAM, $criteria, 'id', 'channel_id', " {criteria_value} "));
	
	
}


	include dirname(__FILE__).'/uigen-generate__code.php';
	$objPage->display('tempphpridload.php');

?>
