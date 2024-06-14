<?
 
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];

set_time_limit( 6000);


	$param = "";
	$SQL_CRITERIA = "";
	$objCriteria = json_decode(stripslashes($criteria));
	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}
		
		$region_id = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_region_id', '', "{criteria_value}");
		$enddate = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'obj_search_chk_inventory_dateend', '', "{criteria_value}");
		
	}
	
	 
	$data = array();
 
		$sql = "
		 SET NOCOUNT ON

		DECLARE @enddate as smalldatetime;
		SET @enddate = '$enddate';
		EXEC poshe_RptSalesTargetbyMonth '$region_id',@enddate ";
		$rs  = $conn->Execute($sql);
	
 
		 
	while (!$rs->EOF) {
		unset($obj);
		
		$branch_id				=   trim($rs->fields['branch_id']);
		$branch_name			=   trim($rs->fields['branch_name']);
	 
		
		
		$nett_1					=   (float) trim($rs->fields['1_nett']);
		$nett_2					=	(float) trim($rs->fields['2_nett']);
		$nett_3					=	(float) trim($rs->fields['3_nett']);
		$nett_4					=	(float) trim($rs->fields['4_nett']);
		$nett_5					=	(float) trim($rs->fields['5_nett']);
		$nett_6					=	(float) trim($rs->fields['6_nett']);
		$nett_7					=	(float) trim($rs->fields['7_nett']);
		$nett_8					=	(float) trim($rs->fields['8_nett']);
		$nett_9					=	(float) trim($rs->fields['9_nett']);
		$nett_10				=	(float) trim($rs->fields['10_nett']);
		$nett_11				=	(float) trim($rs->fields['11_nett']);
		$nett_12				=	(float) trim($rs->fields['12_nett']);
		
		$nett					=	(float) trim($rs->fields['NETT']);
		$vt						=	(float) trim($rs->fields['VT']);
		$VTP					=   (float) trim($rs->fields['VTP']);
		
		
		
		$vb_1					=   (float) trim($rs->fields['1_VB']);
		$vb_2					=	(float) trim($rs->fields['2_VB']);		
		$vb_3					=	(float) trim($rs->fields['3_VB']);
		$vb_4					=	(float) trim($rs->fields['4_VB']);
		$vb_5					=	(float) trim($rs->fields['5_VB']);
		$vb_6					=	(float) trim($rs->fields['6_VB']);
		$vb_7					=	(float) trim($rs->fields['7_VB']);
		$vb_8					=	(float) trim($rs->fields['8_VB']);
		$vb_9					=	(float) trim($rs->fields['9_VB']);
		$vb_10					=	(float) trim($rs->fields['10_VB']);
		$vb_11					=	(float) trim($rs->fields['11_VB']);
		$vb_12					=	(float) trim($rs->fields['12_VB']);
	  
		$vt_1					=   (float) trim($rs->fields['1_VT']);
		$vt_2					=	(float) trim($rs->fields['2_VT']);
		$vt_3					=	(float) trim($rs->fields['3_VT']);
		$vt_4					=	(float) trim($rs->fields['4_VT']);
		$vt_5					=	(float) trim($rs->fields['5_VT']);
		$vt_6					=	(float) trim($rs->fields['6_VT']);
		$vt_7					=	(float) trim($rs->fields['7_VT']);
		$vt_8					=	(float) trim($rs->fields['8_VT']);
		$vt_9					=	(float) trim($rs->fields['9_VT']);
		$vt_10					=	(float) trim($rs->fields['10_VT']);
		$vt_11					=	(float) trim($rs->fields['11_VT']);
		$vt_12					=	(float) trim($rs->fields['12_VT']);
	 
		
		$vt_1p					=   (float) trim($rs->fields['1_VTP']);
		$vt_2p					=	(float) trim($rs->fields['2_VTP']);
		$vt_3p					=	(float) trim($rs->fields['3_VTP']);
		$vt_4p					=	(float) trim($rs->fields['4_VTP']);
		$vt_5p					=	(int) 	trim($rs->fields['5_VTP']);
		$vt_6p					=	(float) trim($rs->fields['6_VTP']);
		$vt_7p					=	(float) trim($rs->fields['7_VTP']);
		$vt_8p					=	(float) trim($rs->fields['8_VTP']);
		$vt_9p					=	(float) trim($rs->fields['9_VTP']);
		$vt_10p					=	(float) trim($rs->fields['10_VTP']);
		$vt_11p					=	(float) trim($rs->fields['11_VTP']);
		$vt_12p					=	(float) trim($rs->fields['12_VTP']);

		
		
		$nett_1					=   (float) trim($rs->fields['1_nett']);
		$nett_2					=	(float) trim($rs->fields['2_nett']);
		$nett_3					=	(float) trim($rs->fields['3_nett']);
		$nett_4					=	(float) trim($rs->fields['4_nett']);
		$nett_5					=	(float) trim($rs->fields['5_nett']);
		$nett_6					=	(float) trim($rs->fields['6_nett']);
		$nett_7					=	(float) trim($rs->fields['7_nett']);
		$nett_8					=	(float) trim($rs->fields['8_nett']);
		$nett_9					=	(float) trim($rs->fields['9_nett']);
		$nett_10				=	(float) trim($rs->fields['10_nett']);
		$nett_11				=	(float) trim($rs->fields['11_nett']);
		$nett_12				=	(float) trim($rs->fields['12_nett']);
		
		$nett					=	(float) trim($rs->fields['NETT']);
		$vt						=	(float) trim($rs->fields['VT']);
		$VTP					=   (float) trim($rs->fields['VTP']);

		
 
	

		unset($obj);
		$obj->branch_id			=	$branch_id;
		$obj->branch_name		=	$branch_name;
		
		$obj->vb_1				=	$vb_1;
		$obj->vt_1				=	$vt_1;
		$obj->nett_1			=	$nett_1;
		$obj->vt_1P				=	$vt_1p;
		
		$obj->vb_2				=	$vb_2;
		$obj->vt_2				=	$vt_2;
		$obj->nett_2			=	$nett_2;
		$obj->vt_2P				=	$vt_2p;
						
		$obj->vb_3				=	$vb_3;
		$obj->vt_3				=	$vt_3;
		$obj->nett_3			=	$nett_3;
		$obj->vt_3P				=	$vt_3p;

 

		$obj->vb_4				=	$vb_4;
		$obj->vt_4				=	$vt_4;
		$obj->nett_4			=	$nett_4;
		$obj->vt_4P				=	$vt_4p;
				
		$obj->vb_5				=	$vb_5;
		$obj->vt_5				=	$vt_5;
		$obj->nett_5			=	$nett_5;
		$obj->vt_5P				=	$vt_5p;
				
		$obj->vb_6				=	$vb_6;
		$obj->vt_6				=	$vt_6;
		$obj->nett_6			=	$nett_6;
		$obj->vt_6P				=	$vt_6p;
		
		
		$obj->vb_7				=	$vb_7;
		$obj->vt_7				=	$vt_7;
		$obj->nett_7			=	$nett_7;
		$obj->vt_7P				=	$vt_7p;		
		 
		
		$obj->vb_8				=	$vb_8;
		$obj->vt_8				=	$vt_8;
		$obj->nett_8			=	$nett_8;		
		$obj->vt_8P				=	$vt_8p;		
		
				
		$obj->vb_9				=	$vb_9;
		$obj->vt_9				=	$vt_9;
		$obj->nett_9			=	$nett_9;		
		$obj->vt_9P				=	$vt_9p;		
				
		$obj->vb_10				=	$vb_10;
		$obj->vt_10				=	$vt_10;
		$obj->nett_10			=	$nett_10;				
		$obj->vt_10P			=	$vt_10p;		
				
		$obj->vb_11				=	$vb_11;
		$obj->vt_11				=	$vt_11;
		$obj->nett_11			=	$nett_11;				
		$obj->vt_11P			=	$vt_11p;		
		
				
		$obj->vb_12				=	$vb_12;
		$obj->vt_12				=	$vt_12;
		$obj->nett_12			=	$nett_12;				
		$obj->vt_12P			=	$vt_12p;		
		
		$obj->VTP				=	$VTP;
		$obj->vb				=	$vb;
		$obj->vt				=	$vt;
		$obj->nett				=	$nett;


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