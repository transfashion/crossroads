<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'region_id', 'region_id', "refParser");	

	
}


if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_regionbranch WHERE $SQL_CRITERIA ORDER BY branch_id DESC";
} else {
	$sql = "SELECT * FROM master_regionbranch ORDER BY branch_id  DESC";
}


//$sql = "select * from master_branch";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
$b = array();
$data = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->branch_id = $rs->fields['branch_id'];
	$branch_id = $rs->fields['branch_id'];
    
	$SQLB = "SELECT branch_name, branch_type FROM master_branch WHERE branch_id ='$branch_id'";
    $rsB = $conn->execute($SQLB);
	$obj->branch_name = $rsB->fields['branch_name'];
	$obj->branch_type = $rsB->fields['branch_type'];
	//$data[] = $obj;
	
	$b[] = array(
		'branch_id' => $obj->branch_id, 
		'branch_name' => $obj->branch_name,
		'branch_type' => $obj->branch_type
	);
	
	$rs->MoveNext();
}

function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

aasort($b, 'branch_name');


foreach ($b as $branch) {
	unset($obj);
	$obj->branch_id = $branch['branch_id'];
	$obj->branch_name = $branch['branch_name'];
	$obj->branch_type = $branch['branch_type'];
	$data[] = $obj; 
}


$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>