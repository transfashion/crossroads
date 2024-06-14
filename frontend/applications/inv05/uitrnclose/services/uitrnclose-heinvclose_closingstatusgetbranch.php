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
	$region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'region_id', '', "{criteria_value}");
    $heinvclosingstatus_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'heinvclosingstatus_id', '', "{criteria_value}"); 		
}

            
       
            $sql =  "SELECT branch_id FROM master_regionbranch WHERE region_id = '$region_id'";
            $rs= $conn->Execute($sql);
            
            WHILE (!$rs->EOF)
            {
                unset($obj);
                $branch_id = $rs->fields['branch_id'];
                
                $obj->branch_id = $branch_id;
                
                $sqlB = "SELECT branch_name FROM master_branch WHERE branch_id = '$branch_id'";
                $rsB  = $conn->execute($sqlB);
                $branch_name = $rsB->fields['branch_name'];
                
                $obj->branch_name = $branch_name;
                $data[] = $obj;
                
                $rs->MoveNext();
            }
             
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>