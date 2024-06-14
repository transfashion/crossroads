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
    $datestart = $strIDs[3];
    $dateend = $strIDs[4];
    $moname = $strIDs[5];
    $hidevalue = $strIDs[6];
    $blnurut = $strIDs[7];

    $GLOBALQTY = 0;
    $GLOBALVALUE = 0;

    	$data = array();
		$sql = "
		SET NOCOUNT ON
		EXEC inv05_RptClosingReportSummaryDetil '$dateend', '$region_id', NULL, NULL";
		$rs  = $conn->Execute($sql);
	   $totalCount=$rs->recordCount();
    
   
   	$rs  = $conn->Execute($sql);
	while (!$rs->EOF)
	{
	
		$heinv_id   = $rs->fields['heinv_id'];
		$branch_id  = $rs->fields['branch_id'];
		$BEG_VAL 	= $rs->fields['BEG_VAL'];
		$RV         = $rs->fields['RV'];
		$RV_VAL     = $rs->fields['RV_VAL'];
		$SL         = $rs->fields['SL'];
		$SL_VAL     = $rs->fields['SL_VAL'];

		$SUM_RV 		    += $RV;
		$SUM_RV_VAL         += $RV_VAL;
		$SUM_SL 		    += $SL;
		$SUM_SL_VAL         += $SL_VAL;
		$rs->MoveNext();
	}
   
     
   
	/* HITUNG NILAI SALES di tanggal ini */
	$sql = "
		declare @date_start as smalldatetime;
		declare @date_end as smalldatetime;
		declare @region_id as varchar(5);

		SET @date_start = '$datestart';
		SET @date_end   = '$dateend';
		SET @region_id = '$region_id';

		SET NOCOUNT ON;

		SELECT
		QTY=SUM(bondetil_qty),
		GROSS =SUM(itemgrossori),
		NETT=SUM(nett)
		FROM dbo.view_hepos_bonlist_1 A
		WHERE
			A.bon_isvoid = 0
		AND A.region_id = @region_id
		AND convert(varchar(10),A.bon_date,120)>=convert(varchar(10),@date_start,120)
		AND convert(varchar(10),A.bon_date,120)<=convert(varchar(10),@date_end,120)
	";
    
    
    
	$rs = $conn->Execute($sql);
	$SUM_BON_QTY      = (float) $rs->fields['QTY'];
	$SUM_BON_GROSS    = (float) $rs->fields['GROSS'];
	$SUM_BON_NETT     = (float) $rs->fields['NETT'];
	
	unset($SUMOBJ);
	$SUMOBJ->RV 		     = $SUM_RV;
	$SUMOBJ->RV_VAL 	     = $SUM_RV_VAL;
	$SUMOBJ->SL              = $SUM_SL;
	$SUMOBJ->SL_VAL          = $SUM_SL_VAL;
	$SUMOBJ->BON_QTY         = $SUM_BON_QTY;
	$SUMOBJ->BON_GROSS       = $SUM_BON_GROSS;
	$SUMOBJ->BON_NETT        = $SUM_BON_NETT;


	//$DATA["$region_id"]["$moname"] = $SUMOBJ;
	
   
        
        unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
        $obj->blnurut			=   $blnurut;
		$obj->namabulan			=   $moname;
        $obj->columnurut		=	1;
		$obj->columntype		=	"RV";		
		$obj->nilai   			=	$SUM_RV;   
		$data[] = $obj;
        
        unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
        $obj->blnurut			=   $blnurut;
		$obj->namabulan			=   $moname;
        $obj->columnurut		=	2;
		$obj->columntype		=	"RV_VALUE";		
		$obj->nilai   			=	$SUM_RV_VAL;        
		$data[] = $obj;
        
        unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
        $obj->blnurut			=   $blnurut;
		$obj->namabulan			=   $moname;
        $obj->columnurut		=	3;
		$obj->columntype		=	"SL";		
		$obj->nilai   			=	$SUM_SL;        
		$data[] = $obj;
        
        unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
        $obj->blnurut			=   $blnurut;
		$obj->namabulan			=   $moname;
        $obj->columnurut		=	4;
		$obj->columntype		=	"COGS";		
		$obj->nilai   			=	$SUM_SL_VAL;        
		$data[] = $obj;
        
        unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
        $obj->blnurut			=   $blnurut;
		$obj->namabulan			=   $moname;
        $obj->columnurut		=	5;
		$obj->columntype		=	"BON.QTY";		
		$obj->nilai   			=	$SUM_BON_QTY;        
		$data[] = $obj;
        
        unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
        $obj->blnurut			=   $blnurut;
		$obj->namabulan			=   $moname;
        $obj->columnurut		=	6;
		$obj->columntype		=	"BON.GROSS";		
		$obj->nilai   			=	$SUM_BON_GROSS;             
		$data[] = $obj;
        
        
        unset($obj);
        $obj->region_id			=	$region_id;
        $obj->region_name		=	$region_name;
        $obj->blnurut			=   $blnurut;
		$obj->namabulan			=   $moname;
        $obj->columnurut		=	7;
		$obj->columntype		=	"BON.NETT";		
		$obj->nilai   			=	$SUM_BON_NETT;        
		$data[] = $obj;
        
          
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>