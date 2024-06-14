<?
if (!defined('__SERVICE__')) {
	die("access denied");
}



	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__JSONDATA	= $_POST['JSONDATA'];
	$__POSTDATA = json_decode(stripslashes($__JSONDATA));
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;



	$FileProcessor = dirname(__FILE__).'/'.basename(__FILE__, "-save.php");

	try {
		$conn->BeginTrans();
	
		
		include $FileProcessor.'-save_header.php';
		
		if (empty($__POSTDATA->H)) {
			/* Data H tidak ada, tidak mengalami perubahan, data diambil berdasar __ID dari database */
			$sql = "select * from ".$__CONF['H']['TABLE_NAME']." where ".$__CONF['H']['PRIMARY_KEY']."='$__ID'";
			$rs  = $conn->Execute($sql);
			
			$__POSTDATA->H->inventorymovingtype_id = $rs->fields['inventorymovingtype_id'];
			$__POSTDATA->H->region_id = $rs->fields['region_id_source'];
			$__POSTDATA->H->branch_id_from = $rs->fields['branch_id_source'];
			$__POSTDATA->H->branch_id_to = $rs->fields['branch_id_target'];

		}
		
		
		if ($__POSTDATA->H->inventorymovingtype_id=='AS') {
			include $FileProcessor.'-save_detilproduct.php';
			include $FileProcessor.'-save_detilcomponent.php';		
		} else {
			include $FileProcessor.'-save_detilitem.php';
			include $FileProcessor.'-save_detilexception.php';
		}	
		
		
		if (empty($__POSTDATA->H)) {
			unset($obj);
			$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
			$obj->{$__CONF['H']['MODIFYBY']} 	= $__USERNAME;
			$obj->{$__CONF['H']['MODIFYDATE']} 	= SQLUTIL::SQL_GetNowDate();
			$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
			$conn->Execute($SQL);
			$__RESULT[0]->H = $obj;			
		}
			
		$conn->CommitTrans();
	} catch (Exception $e) {
		$conn->RollbackTrans();
		$msg = $e->getMessage();
		$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
	}

	
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $__RESULT;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));

?>