<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$limit 		= $_POST['limit'];
$start 		= $_POST['start'];
$criteria	= $_POST['criteria'];


//$__POSTDATA = json_decode(stripslashes($__JSONDATA));
//print $criteria;


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
	}
	
	$param = "";
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_customer_id', 'customer_id', "refParser");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_customer_name', 'customer_namefull', "{db_field} LIKE '%{criteria_value}%'");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', "{db_field} = '{criteria_value}'");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_branch_id', 'branch_id', "{db_field} = '{criteria_value}'");

	$purrangemethod = SQLUTIL::BuildCriteria(&$param, $criteria, 'obj_search_chk_purchaserange', '', "{criteria_value}");
	if ($purrangemethod) {
		$rangeval1 = SQLUTIL::BuildCriteria(&$param, $criteria, 'obj_search_chk_purchaserangeval1', '', "{criteria_value}");

		if ($purrangemethod=="More Than") {
			$rangeval2 = SQLUTIL::BuildCriteria(&$param, $criteria, 'obj_search_chk_purchaserageless', '', "{criteria_value}");
			if ($rangeval2) {
				$sql = " ( buy >= $rangeval1 and buy <= $rangeval2 )";
			} else {
				$sql = " buy >= $rangeval1 ";
			}
		
		

		} else {
			$sql = " buy <= $rangeval1 ";
		}
		if ($SQL_CRITERIA) {
			$SQL_CRITERIA .= "AND $sql ";
		} else {
			$SQL_CRITERIA .= " $sql ";
		}
	}

	
}



//print $SQL_CRITERIA;

if ($SQL_CRITERIA) {
	$sql = "SELECT * FROM master_customer WHERE $SQL_CRITERIA ORDER BY customer_namefull";
} else {
	$sql = "SELECT * FROM master_customer ORDER BY customer_namefull";
}



if ($limit) {
	$rs = $conn->Execute($sql);
	$totalCount = $rs->recordCount();
	$rs = $conn->SelectLimit($sql, $limit, $start);
} else {
	$rs = $conn->Execute($sql);
	$totalCount = $rs->recordCount();
}


$data = array();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->customer_id 			= $rs->fields['customer_id'];
	$obj->customer_title 		= $rs->fields['customer_title'];
	$obj->customer_namefull 	= $rs->fields['customer_namefull'];
	$obj->customer_address 		= $rs->fields['customer_address'];
	$obj->customer_city 		= $rs->fields['customer_city'];
	$obj->gender_id 			= $rs->fields['gender_id'];
	$obj->region 				= $rs->fields['region'];

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