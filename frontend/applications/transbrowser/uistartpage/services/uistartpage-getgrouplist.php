<?
if (!defined('__SERVICE__')) {
	die("access denied");
}



$username = $_SESSION["username"];

$sql = "select A.* 
		from master_group A inner join master_usergroup B on A.group_id = B.group_id
		WHERE B.username = '$username' AND A.group_isdisabled=0
		order by A.group_name";

unset($data);
$data = array();        
$rs = $conn->Execute($sql);
while (!$rs->EOF) {

	$obj = new Object();
	$obj->group_id 		= $rs->fields["group_id"];
    $obj->group_name 	= $rs->fields["group_name"];
    $obj->group_descr 	= $rs->fields["group_descr"];
    $data[] = $obj;
    
	$rs->MoveNext();
}      
$objResult = new WebResultObject("objResult");
$objResult->totalCount = count($data);
$objResult->success = true;
$objResult->data = $data;
		
print(stripslashes(json_encode($objResult)));
?>