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

    $conn->Execute("UPDATE transaksi_heinvclosingstatus SET heinvclosingstatus_iscompleted=1 WHERE heinvclosingstatus_id='$heinvclosingstatus_id'");
  

 	$sql = "
		update
			B
		SET
			B.heinv_lastcost = A.COST,
			B.heinv_lastcostid = A.heinvclosingstatus_id
		FROM
			transaksi_heinvclosingstatusdetil A inner join master_heinv B
			ON A.heinvclosingstatus_id='$heinvclosingstatus_id'
			   AND A.heinv_id=B.heinv_id
    ";
	$conn->execute($sql);




  
    unset($obj);
    $obj->message=1;
    $data[] = $obj;
            
            
             
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>
