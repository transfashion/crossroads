<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}
 
$__ID 		= $_POST['__ID'];

//print 'test';
/*
define('ADODB_DIR', 'adodb');
require_once ADODB_DIR.'/adodb-exceptions.inc.php';
require_once ADODB_DIR.'/adodb.class.php';



$db_local[type] = 'ado_mssql';
$db_local[host] = 'localhost\SQLEXPRESS';
$db_local[name] = 'E_FRM2_MGP';
$db_local[user] = 'sa';
$db_local[pass] = 'rahasia';


$db_local[type] = 'ado_mssql';
$db_local[host] = '172.16.10.20';
$db_local[name] = 'E_FRM2_MGP';
$db_local[user] = 'sa';
$db_local[pass] = 'meg@tower';


try {
	print "Connecting to ".$db_local[name]."@".$db_local[host]."... ";
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection($db_local[type]);
	$DSN_LOCAL  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db_local[host]."; DATABASE=".$db_local[name]."; UID=".$db_local[user]."; PWD=".$db_local[pass].";";
	$conn->Connect($DSN_LOCAL);
	print "Connected.\n\n";
	
} catch (exception $e) {
	print $e->GetMessage();	
}

*/





$price_id =$__ID;

$sql = "select * from dbo.transaksi_heinvprice where price_id = '$price_id' ";
$rs  = $conn->Execute($sql);
if (!$rs->fields['price_isverified']) {
 /*
	print "\n\n";
	print "$price_id belum diposting!. Price tidak bisa di generate";
	print "\n\n";
	print "Tekan sembarang tombol... ";
	fscanf(STDIN, "%s\n", $result);
	*/
	$dbErrors = new WebResultErrorObject("0x00000001", "$price_id belum diverifikasi!. Price tidak bisa di generate");
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = false;
	$objResult->errors = $dbErrors;
	die(stripslashes(json_encode($objResult)));	
}

$pricingtype_id = $rs->fields['pricingtype_id'];
if (!in_array($pricingtype_id, array('REG', 'MDN', 'LOC'))) {
	$dbErrors = new WebResultErrorObject("0x00000001", "$price_id bukan reguler/Markdown/LocalPricing, tidak bisa di generate.");
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = false;
	$objResult->errors = $dbErrors;
	die(stripslashes(json_encode($objResult)));
} 
 


 
try {
	$conn->BeginTrans();
	
	
	$BATCH = time();
	$sql = "select * from transaksi_heinvpricedetil WHERE  price_id = '$price_id' ";
	$rs  = $conn->Execute($sql);
	
	$failed=0;
	$msg="";



	while (!$rs->EOF) {
		$heinv_id = $rs->fields['heinv_id'];
		$newprice = 1*$rs->fields['heinv_price01'];
		$newdisc  = 1*$rs->fields['heinv_pricedisc01']; 
		
		$sql = "select * from master_heinv where heinv_id = '$heinv_id' ";
		$rsI = $conn->Execute($sql);
		
		
		
		
		if ($rsI->recordCount()) {
		
				//print "Updating $heinv_id from $price_id ...";
				//$heinv_priceori	=	1*$rsI->fields['heinv_priceori'];
				
				$lastprice =  (float) $rsI->fields['heinv_price01'];
				$lastdisc  = (float)  $rsI->fields['heinv_pricedisc01']; 
				$issp      = 1*$rs->fields['heinv_isSP']; 
				$isadjgross      = (float) $rs->fields['heinv_isadjgross']; 
				
				/* update price */		
				unset($obj);
				if ($issp==0)
				{
				 	if ($lastprice!=$newprice)
				 	{
						//$heinv_priceori = $newprice; 	 	
				 	}
				}

	            $sqlori = "select heinv_priceori from master_heinv where heinv_id = '$heinv_id' ";
				$rsori  = $conn->Execute($sqlori);
				$current_priceori = (float) $rsori->fields['heinv_priceori'];
				if ($current_priceori==0) {
					$obj->heinv_priceori = $newprice;
				}
				
				unset($objgross);
				if ($isadjgross==1)
				{
				 
				 	$objgross->heinv_id = $heinv_id;
				 	$sqlLine = "select tgl = getdate(),line =ISNULL(max(heinvpriceadj_line),0) from master_heinvpriceadj WHERE heinv_id = '$heinv_id'";
				 	$rsLine = $conn->execute($sqlLine);
				 	$line = 10 + 1*$rsLine->fields['line'];
				 	$tgl = $rsLine->fields['tgl'];
				 	$objgross->heinv_id = $heinv_id;
				 	$objgross->heinvpriceadj_line = $line;
				 	$objgross->heinvpriceadj_date = $tgl;
				 	$objgross->heinvpriceadj_value =  (float) $newprice - (float) $current_priceori ;
				 	$objgross->pricing_id = $price_id;
				 	$SQL = SQL_InsertFromObject("master_heinvpriceadj",$objgross);

					if ($current_priceori > 0)
					{
						$conn->execute($SQL);
					}
				}
				
				
				/* Update ke Master HEINV */
				$obj->heinv_price01 = $newprice;
				$obj->heinv_pricedisc01 = $newdisc;
				$obj->heinv_lastpriceid = $price_id;
				$SQL = SQL_UpdateFromObject("master_heinv", $obj, "heinv_id = '$heinv_id' ");
				$conn->Execute($SQL);
				

				/* Update CheckList Nya */
				$SQL = "
				Update transaksi_heinvprice 
				SET price_isgenerated=1
				WHERE price_id = '$price_id'";
				$conn->execute($SQL);

				$failed=0;
				$msg="";
							

				$sql = "SELECT heinvpricelog_line = MAX(heinvpricelog_line) FROM master_heinvpricelog WHERE heinv_id = '$heinv_id' ";	
				$rsN = $conn->Execute($sql);
				$newline = 1+(1*$rsN->fields['heinvpricelog_line']);			
				/* insert to master_heinvpricelog */
				unset($obj);
				$obj->heinv_id = $heinv_id;
				$obj->heinvpricelog_line = $newline;
				$obj->heinvpricelog_batch = $BATCH;
				$obj->heinvpricelog_batchdate = date('Y-m-d');
				$obj->heinv_lastprice = $lastprice;
				$obj->heinv_lastdisc = $lastdisc;
				$obj->heinv_newprice = $newprice;
				$obj->heinv_newdisc = $newdisc;
				$obj->heinv_issp = $issp;
				$obj->heinv_pricingslot = '01';
				$obj->heinvprice_id	    = $price_id;	
				$SQL = SQL_InsertFromObject("master_heinvpricelog", $obj);
				$conn->Execute($SQL);
				
				
				/* insert to master_heinv tlog */
				unset($obj);
				$SQL = "SELECT line=MAX(log_line) FROM transaksi_tlog WHERE id='$heinv_id'";
				$rsN  = $conn->Execute($SQL);
				$newline = 1+(1*$rsN->fields['line']);

				$obj->id			= $heinv_id;
				$obj->log_line		= $newline;
				$obj->log_action	= "PRICE UPDATE";
				$obj->log_table		= "master_heinv";
				$obj->log_descr		= "generated from PHP ($price_id)";
				$obj->log_lastvalue	= "$lastprice/$lastdisc";
				$obj->log_username	= "php";
				$SQL = SQL_InsertFromObject("transaksi_tlog", $obj);
				$conn->Execute($SQL);
	
		
		} else {
		 
 				$failed=1;
				$msg=$heinv_id . ' Not Found ' ;
		}
		
		$rs->MoveNext();
	}
	


	// DONE
	/* set pricing_id as generated */
	unset($obj);
	$obj->price_isgenerated = 1;
	$SQL = SQL_UpdateFromObject("transaksi_heinvprice", $obj, "price_id = '$price_id' ");
	//$conn->Execute($SQL);
	
	
	
	// Syncronisasi ke Server Couch
	// tambahan
	// Agung Nugroho
	// 4 Desember 2018
	$tosync_docid = $price_id;
	$tosync_action = 'UPDATEPRICE';
	$fileinc = dirname(__FILE__).'/../../../../syndoctocouch.inc.php' ;
	include($fileinc);
	
	
	
	
	unset($obj);
	$obj->failed  	= $failed;
	$obj->message 	= $msg;
	$data = array($obj);	


		
	/* insert to pricing tlog */
	unset($obj);
	$SQL = "SELECT line=MAX(log_line) FROM transaksi_tlog WHERE id='$price_id'";
	$rsN  = $conn->Execute($SQL);
	$newline = 1+(1*$rsN->fields['line']);

	$obj->id			= $price_id;
	$obj->log_line		= $newline;
	$obj->log_action	= "PRICE UPDATE";
	$obj->log_table		= "transaksi_heinvprice";
	$obj->log_descr		= "generated from PHP";
	$obj->log_lastvalue	= "";
	$obj->log_username	= "php";
	$SQL = SQL_InsertFromObject("transaksi_tlog", $obj);
	//$conn->Execute($SQL);




	$conn->CommitTrans();	
} catch (exception $e) {
	$conn->RollbackTrans();
	print $e->GetMessage();	
}




	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);

			
	print(stripslashes(json_encode($objResult)));
   

/* ********************************************************************************************************8 */

	 function SQL_UpdateFromObject($tablename, $obj, $criteria) {
		if (!is_object($obj)) return;
		foreach ( $obj as $name => $value ) {
			if (is_object($value)) {
				$value = "0";
			}
			
			$val = "'$value'";
			if ($val=="'__DBNULL__'") {
				$val = "NULL";
			}			
			$updates[] = "$name = $val";		
		}		
		
		$_UPDATES = implode(", ", $updates);
		$SQL	 = "UPDATE $tablename ";
		$SQL	.= "SET ";
		$SQL 	.= $_UPDATES;
		
		if ($criteria) {
			$SQL .= " WHERE $criteria ";
		}
	
		return $SQL;
			
	}
	
	

	 function SQL_InsertFromObject($tablename, $obj) {
		if (!is_object($obj)) {
			return;
		}
	
		foreach ( $obj as $name => $value ) {
			if (is_object($value)) {
				$value = "";
			}
			$fields[] = $name; 	
			$data[]	  = $value;	
		}
	

		
		$_FIELDS  = implode(", ", $fields);
		$_VALUES  = implode("', '", $data);
		$SQL	 = " INSERT INTO $tablename ";
		$SQL	.= " ($_FIELDS) "; 
		$SQL	.= " VALUES ";
		$SQL	.= " ('$_VALUES') ";		
		
		return $SQL;	
	}


?>