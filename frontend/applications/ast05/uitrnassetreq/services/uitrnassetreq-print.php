<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];
$doc        = $_GET['doc'];


$sqlH = "select 
A.assetrequest_id,
A.assetrequest_date,
A.assetrequest_duedate,
A.assetrequest_descr,
A.region_id,
A.branch_id,
A.strukturunit_id,
A.owner_strukturunit_id,
A.assetrequest_createby,
B.project_id,
B.assetclass_name,
B.assetrequestdetil_line,
B.assetrequestdetil_qty,
B.assetrequestdetil_descr
from transaksi_assetrequest A inner join transaksi_assetrequestdetil B
on A.assetrequest_id = B.assetrequest_id 
where A.assetrequest_id = '$id'";

$data = array();
$rs = $conn->Execute($sqlH);

while (!$rs->EOF) {
 
 	unset($obj);
 
 	$nomor++;
	$region_id = trim($rs->fields['region_id']);
		$SQLB					= 	"SELECT region_name FROM master_region WHERE region_id = '$region_id'";
	    $rsB 					= 	$conn->execute($SQLB);
		$region_name 			= 	trim($rsB->fields['region_name']);
		$obj->region_id			=	$region_id;
		$obj->region_name		=	$region_name;

	$branch_id = trim($rs->fields['branch_id']);
		$SQLC					= 	"SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
	    $rsC 					= 	$conn->execute($SQLC);
		$branch_name 			= 	trim($rsC->fields['branch_name']);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		
	$strukturunit_id = trim($rs->fields['strukturunit_id']);
		$SQLD					= 	"SELECT strukturunit_name FROM master_strukturunit WHERE strukturunit_id = '$strukturunit_id'";
	    $rsD 					= 	$conn->execute($SQLD);
		$strukturunit_name 			= 	trim($rsD->fields['strukturunit_name']);
		$obj->strukturunit_id			=	$strukturunit_id;
		$obj->strukturunit_name			=	$strukturunit_name;					
		
	$owner_strukturunit_id = trim($rs->fields['owner_strukturunit_id']);
		$SQLE					= 	"SELECT strukturunit_name FROM master_strukturunit WHERE strukturunit_id = '$owner_strukturunit_id'";
	    $rsE 					= 	$conn->execute($SQLE);
		$owner_strukturunit_name 			= 	trim($rsE->fields['strukturunit_name']);
		$obj->owner_strukturunit_id			=	$owner_strukturunit_id;
		$obj->owner_strukturunit_name			=	$owner_strukturunit_name;					
		
	
	$project_id = trim($rs->fields['project_id']);
		$SQLE					= 	"SELECT project_name FROM transaksi_project WHERE project_id = '$project_id'";
	    $rsE 					= 	$conn->execute($SQLE);
		$project_name 			= 	trim($rsE->fields['project_name']);
		$obj->project_id			=	$project_id;
		$obj->project_name			=	$project_name;

	$obj->assetrequest_id 			= $rs->fields['assetrequest_id'];
	$obj->assetrequest_date 		= SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['assetrequest_date']));
	$obj->assetrequest_duedate 		= SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['assetrequest_duedate']));
	$obj->owner_strukturunit_id 	= $rs->fields['owner_strukturunit_id'];
	$obj->assetclass_name		 	= $rs->fields['assetclass_name'];
	$obj->assetrequest_descr 		= $rs->fields['assetrequest_descr'];
	$obj->assetrequestdetil_line 	= $rs->fields['assetrequestdetil_line'];
	$obj->assetrequestdetil_qty 	= $rs->fields['assetrequestdetil_qty'];
	$obj->assetrequestdetil_descr 	= $rs->fields['assetrequestdetil_descr'];
	$obj->nomor = $nomor;
	
	$createby = trim($rs->fields['assetrequest_createby']);
		$SQLE					= 	"SELECT user_fullname FROM master_user WHERE username = '$createby'";
	    $rsE 					= 	$conn->execute($SQLE);
		$createby	 			= 	trim($rsE->fields['user_fullname']);
		$obj->createby			=	$createby;
			
	$obj->verified1 = "Agung Nugroho";
	$obj->verified2 = "                    ";
	$obj->approved1 = "Michael Rully";
	$obj->approved2 = "Pitriyanti W";

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