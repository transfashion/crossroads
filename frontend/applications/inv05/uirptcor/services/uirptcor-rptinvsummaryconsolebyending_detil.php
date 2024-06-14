<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$ids 		= $_POST['ids'];
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
			
	}

 

	$strIDs = explode("|",$ids);
	$region_id = $strIDs[0];
	$region_name = $strIDs[1];
	$periode_id = $strIDs[2];
	$branch_id = $strIDs[3];
	$season_id = $strIDs[4];
    $hidevalue = $strIDs[5];

    $GLOBALQTY = 0;
    $GLOBALVALUE = 0;


	
    	$data = array();
		$sql = "
		SET NOCOUNT ON
		EXEC inv05_RptCor_SummaryConsoleByEnding '$region_id','$periode_id','$branch_id','$season_id'  ";
		$rs  = $conn->Execute($sql);
	
	$totalCount=$rs->recordCount();
    
    $shortperiod = substr($periode_id,0,2);
    $shortperiod = (float) ($shortperiod - 3);
    
    if (strlen($shortperiod) == 1)
    {
        $shortperiod = "0" . $shortperiod;
    }
    
    
       $sqlSea = "SELECT season_id FROM master_season WHERE season_id<'$shortperiod' and season_id = $season_id";
       $rsShort = $conn->execute($sqlSea);
       $seasonCheck =$rsShort->fields['season_id'];
        
        
       if ($seasonCheck)
        {
            $season_id = "OLD";
        }
       else
        {
            $season_id = trim($rs->fields['season_id']);
        }
        
        
        
        
  if (!$totalCount)
  {
        $_QTYEND 		        =	0;
        $_VALEND 		        =	0;
        
        
        if ($season_id=="")
        {
            $season_id = "OLD";
        }
        
		unset($obj);
        
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
		$obj->season_id			=   $season_id;
		$obj->columntype		=	"QTY";		
		$obj->nilai   			=	1*$_QTYEND;        
		$data[] = $obj;
 
        if (!$hidevalue)
        {
     		unset($obj);
            $obj->region_id			=	$region_id;
            $obj->region_name		=	$region_name;
    		$obj->season_id			=	$season_id;
    		$obj->columntype		=	"VAL";		
    		$obj->nilai		     	=	1*$_VALEND;        
    		$data[] = $obj;
        }
 
 
 
 
  }
        
    
	while (!$rs->EOF) {
	   
        $_QTYEND 		        =	(float) trim($rs->fields['QTYEND']);
        $_VALEND 		        =	(float) trim($rs->fields['VALEND']);
       
       
       
       $GLOBALQTY = $GLOBALQTY + $_QTYEND ;
       if (!$hidevalue)
       {
            $GLOBALVALUE = $GLOBALVALUE + $_VALEND ;
       }
    
		unset($obj);
        
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
		$obj->season_id			=   $season_id;
		$obj->columntype		=	"QTY";		
		$obj->nilai   			=	1*$_QTYEND;        
		$data[] = $obj;
 
        if (!$hidevalue)
        {
     		unset($obj);
            $obj->region_id			=	$region_id;
            $obj->region_name		=	$region_name;
    		$obj->season_id			=	$season_id;
    		$obj->columntype		=	"VAL";		
    		$obj->nilai		     	=	1*$_VALEND;        
    		$data[] = $obj;
        }
 
 
 		unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
		$obj->season_id			=   'TOTAL';
		$obj->columntype		=	"QTY";		
		$obj->nilai   			=	$GLOBALQTY;        
		$data[] = $obj;
        
        
        if (!$hidevalue)
        {
            unset($obj);
            $obj->region_id			=	$region_id;
            $obj->region_name		=	$region_name;
    		$obj->season_id			=   'TOTAL';
    		$obj->columntype		=	"VAL";		
    		$obj->nilai   			=	$GLOBALVALUE;        
    		$data[] = $obj;
        }
        
        
        
        
        
 
 
 
		$rs->MoveNext();
	}
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>