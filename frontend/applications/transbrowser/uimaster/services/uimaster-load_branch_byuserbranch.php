<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];



$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}

}


$SQL_BRANCH = " 
                SELECT A.branch_id, A.branch_name, A.branch_type   
				from 
				master_branch A inner join master_userbranch B on A.branch_id = B.branch_id 
                WHERE A.branch_isdisabled = 0 AND B.username=  '$username'
				";

//print $SQL_CRITERIA;
 
$rs = $conn->Execute($SQL_BRANCH);
$totalCount = $rs->recordCount();
$data = array();
while (!$rs->EOF) 
{
	unset($obj);
	$obj->branch_id = $rs->fields['branch_id'];
	$obj->branch_name = $rs->fields['branch_name'];
	$obj->branch_type = $rs->fields['branch_type'];
    $data[] = $obj;
	$rs->MoveNext();		
}
    




$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>