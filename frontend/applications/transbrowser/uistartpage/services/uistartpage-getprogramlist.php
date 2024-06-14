<?
if (!defined('__SERVICE__')) {
	die("access denied");
}



$username = $_SESSION["username"];
$group_id = $_POST['group_id'];


$sql = "select A.* 
        from master_program A inner join master_programgroup B on A.program_id = B.program_id
        where B.group_id = '$group_id' AND A.program_isdisabled=0
		order by A.programtype_id, A.program_title 
		";
 
unset($data);
$data = array();        
$rs = $conn->Execute($sql);
while (!$rs->EOF) {

	$obj = new Object();
	$obj->program_id 		= $rs->fields["program_id"];
    $obj->program_title 	= $rs->fields["program_title"];
    $obj->program_descr 	= $rs->fields["program_description"];
    $obj->program_icon 		= $rs->fields["program_icon"];
    $obj->program_ns 		= $rs->fields["program_ns"];
    $obj->program_dll 		= $rs->fields["program_dll"];
    $obj->program_instance 	= $rs->fields["program_instance"];
    $obj->program_type 		= $rs->fields["programtype_id"];
    $obj->program_issingleinstance = 1*$rs->fields["program_issingleinstance"] ? 1 : 0;
    $obj->program_islocaldll = 1*$rs->fields["program_islocaldll"] ? 1 : 0;
    $obj->program_islocaldb = 1*$rs->fields["program_islocaldb"] ? 1 : 0;
    $obj->program_parameter = $rs->fields["program_parameter"];
    
    $data[] = $obj;
    
	$rs->MoveNext();
}        



$objResult = new WebResultObject("objResult");
$objResult->totalCount = count($data);
$objResult->success = true;
$objResult->data = $data;
		
print(stripslashes(json_encode($objResult)));
?>