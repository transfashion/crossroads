<?
	if (!defined('__SERVICE__')) {
	//	die("access denied");
	}


	require_once dirname(__FILE__)."/pricing-xls-doc.php";


	$username 	= $_SESSION["username"];
	$__ID 		= $_POST['__ID'];
	$__ACTION	= $_POST['__ACTION'];
	$__FILENAME = $_POST['__FILENAME'];


	/* BEGIN: Untuk TEST */
	// require_once dirname(__FILE__).'/../../../../adodb/adodb-exceptions.inc.php';
	// require_once dirname(__FILE__).'/../../../../adodb/adodb.class.php';
	// $__ID = 'PC/05/HBS/HO/200000023';
	// $__FILENAME = 'PC-05-HBS-HO-200000023.xls';
	// $db[type] = 'ado_mssql';
	// $db[host] = '172.18.10.254';
	// $db[name] = 'E_FRM2_BACKUP';
	// $db[user] = 'sa';
	// $db[pass] = 'rahasia';
	// $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	// $conn = &ADONewConnection($db[type]);
	// try {
	// 	$DSN  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db[host]."; DATABASE=".$db[name]."; UID=".$db[user]."; PWD=".$db[pass]."; Connect Timeout=300000";
	// 	$conn->Connect($DSN);
	// } catch (exception $e) { 		
	// 	die($e->getMessage());	
	// } 
	/* END: Untuk Test */



	$sql = "
		select 
		A.price_id,
		A.pricedetil_line,
		B.price_isnewitemprice,
		A.ref_id,
		A.heinvgro_id,
		A.heinvctg_id,
		(select heinvctg_class from master_heinvctg where heinvctg_id=A.heinvctg_id) as heinvctg_name,
		
		isnull(
			(select season_id from transaksi_heorder where heorder_id=A.ref_id),
			(select season_id from master_heinv where heinv_id=A.heinv_id)
		) as season_id,

		A.heinv_id,
		A.heinv_art,
		A.heinv_mat,
		A.heinv_col,
		A.heinv_lastqty,
		A.heinv_age,
		A.heinv_currentpricegross,
		A.heinv_lastprice as heinv_currentprice,
		A.heinv_lastdisc as heinv_currentpricedisc,
		((1-(A.heinv_lastdisc/100))*A.heinv_lastprice) as heinv_currentpricenett,
		A.heinv_ordered,
		A.currency_id,
		A.heinv_fob,
		A.heinv_rate,
		A.heinv_fobidr,
		A.heinv_extracost,
		A.heinv_cost,
		A.heinv_minmf,
		A.heinv_calcmfprice as calcmfprice,
		A.heinv_price_hk,
		A.heinv_price_sin,
		A.heinv_price01 as proposed_price,
		A.heinv_pricedisc01 as proposed_pricedisc
		from transaksi_heinvpricedetil A inner join transaksi_heinvprice B on B.price_id = A.price_id
		where 
		A.price_id='$__ID'
		order by
		price_id, pricedetil_line
	";

	$rs = $conn->Execute($sql);

	$filepath = dirname(__FILE__)."/../../../../updater/pricing/$__FILENAME";
	if (is_file($filepath)) {
		unlink($filepath);
	}



	$doc = new XlsDoc($__ID);

	while (!$rs->EOF) {
		$doc->AddRecord($rs);
		$rs->MoveNext();
	}

	$doc->Compose();
	$doc->Save($filepath);



	
	
	
	unset($obj);
	$obj->failed  	= false;
	$obj->filename 	= $__FILENAME;
	$obj->message 	= "";
	$data = array($obj);

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));	

?>