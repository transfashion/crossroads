<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$criteria	= $_POST['criteria'];
$param = "";
$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$CRITERIA_DB = array();
	while (list($name, $value) = each($objCriteria)) {
		$CRITERIA_DB[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
    $heinvclosingstatus_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'heinvclosingstatus_id', '', "{criteria_value}");
  		
}

            $sql =  "DELETE FROM transaksi_heinvclosingstatusdetil WHERE heinvclosingstatus_id='$heinvclosingstatus_id'";
              $conn->Execute($sql);
            $sql =  "DELETE FROM transaksi_heinvclosingstatus      WHERE heinvclosingstatus_id='$heinvclosingstatus_id'";
            $conn->Execute($sql);
             
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>