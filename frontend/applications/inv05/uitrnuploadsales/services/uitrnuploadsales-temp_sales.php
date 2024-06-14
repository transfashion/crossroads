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
    
 
                
	
        $periode_id   = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'periode_id', '', "{criteria_value}");
	    $bookdate   = SQLUTIL::BuildCriteria($param, &$CRITERIA_DB, 'bookdate', '', "{criteria_value}");
  		$acc_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'acc_id', '', "{criteria_value}");
        $region_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'region_id', '', "{criteria_value}");
        $branch_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'branch_id', '', "{criteria_value}");
        $strukturunit_id   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'strukturunit_id', '', "{criteria_value}");
        $amount   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'amount', '', "{criteria_value}");
        $type   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'type', '', "{criteria_value}");
        $filename   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'FILENAME_DB', '', "{criteria_value}");
  		
}

       
       
       
        
        
        
         $tgl_bookdate = Explode("/", $bookdate);                   
         $BOOKDATE  = ("$tgl_bookdate[2]-$tgl_bookdate[0]-$tgl_bookdate[1]");              
				            
 
        $region_id = str_pad($region_id,5,"0",STR_PAD_LEFT)  ;
        $branch_id = str_pad($branch_id,7,"0",STR_PAD_LEFT)  ;
                         
                         
		unset($obj);
		$obj->periode_id=$periode_id;
        $obj->bookdate=$BOOKDATE;
        $obj->channel_id='MGP';
        $obj->acc_id=$acc_id;
        $obj->region_id=$region_id;
        $obj->branch_id=$branch_id;
        $obj->strukturunit_id=$strukturunit_id;
        $obj->amount=1*$amount;
        $obj->filename=$filename;
        $obj->type=$type;
                                              
		$SQL = SQLUTIL::SQL_InsertFromObject('temp_sales', $obj);
		$conn->Execute($SQL);
        $data[] = $obj;
            
             
             
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>