<?php

	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
	$__JSONDATA	= $_POST['JSONDATA'];
	$__POSTDATA = json_decode(stripslashes($__JSONDATA));
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;
	

	if (empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD'])=='post') {
		$poidsMax = ini_get('post_max_size');
		$dbErrors = new WebResultErrorObject("0x00000001", "Data Overload, your data is too big, maximum allowed size here is $poidsMax");
		$objResult = new WebResultObject("objResult");
		$objResult->totalCount = 1;
		$objResult->success = false;
		$objResult->data = $__RESULT;
		$objResult->errors = $dbErrors;
		die(stripslashes(json_encode($objResult)));		
	}
	
	
 
			$SQL = "
				DECLARE @heinvregister_id as nvarchar(30)
				SET NOCOUNT ON
				SET @heinvregister_id = '$__ID'
				EXEC inv05_PrintLabelImport @heinvregister_id";


	$rs_P = $conn->Execute($SQL);

		while (!$rs_P->EOF) 
		{
		 
		 	$ART = trim($rs_P->fields['heinv_art']);
		 	$MAT = trim($rs_P->fields['heinv_mat']);
		 	$COL = trim($rs_P->fields['heinv_col']);		 	
		 	$PRODUK =trim($rs_P->fields['heinv_produk']);
		 	$BAHAN ='Material: ' . trim($rs_P->fields['heinv_bahan']);
		 	$PEMELIHARAAN ='Pemeliharaan: ' . substr(trim($rs_P->fields['heinv_pemeliharaan']),0,110);
		 	$DIBUATDI ='Dibuat di ' . trim($rs_P->fields['heinv_dibuatdi']);
		 	$FORMAT =trim($rs_P->fields['heinv_format']);
		 	$LAINLAIN =trim($rs_P->fields['heinv_other1']);
		 	$SEASON =trim($rs_P->fields['season_id']);
		 	$HEINVCTG_ID =trim($rs_P->fields['heinvctg_id']);
		 	$HEINVGRO_ID =trim($rs_P->fields['heinvgro_id']);
		 	$HEINVCTG_NAME =trim($rs_P->fields['heinvctg_name']);
		 	$HEINVGRO_NAME =trim($rs_P->fields['heinvgro_name']);
		 	$QTY =1*trim($rs_P->fields['QTY']);
		 	//$QTY =1;

			unset($obj);
			$obj->heinv_art = $ART;
			$obj->heinv_mat = $MAT;
			$obj->heinv_col = $COL;
			$obj->heinv_produk = $PRODUK;
			$obj->heinv_bahan = $BAHAN;
			$obj->heinv_pemeliharaan = $PEMELIHARAAN;
			$obj->heinv_dibuatdi = $DIBUATDI;
			$obj->heinv_format = $FORMAT;
			$obj->heinv_other1 = $LAINLAIN;
			$obj->season_id = $SEASON;
			$obj->heinvctg_id = $HEINVCTG_ID;
			$obj->heinvgro_id = $HEINVGRO_ID;
			$obj->heinvctg_name = $HEINVCTG_NAME;
			$obj->heinvgro_name = $HEINVGRO_NAME;
			$obj->qty = $QTY;
			
			
			
			
			
			
			
			$data[] = $obj;
			
		 	$rs_P->MoveNext();		 	
		 }

 
	
 
		 
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = count($data);
	$objResult->success = true;
	$objResult->data = $data;;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));
 

?> 