<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$id 		= $_POST['id'];
	
	
	unset($data);
	
	set_time_limit(100);





	$sql = "select * from transaksi_inventorymoving where inventorymoving_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->inventorymoving_id = trim($rs->fields['inventorymoving_id']);
	$objh->inventorymoving_date = trim($rs->fields['inventorymoving_date']);
	$objh->inventorymoving_descr = str_replace(array("'", '"'), array("", ""), $rs->fields['inventorymoving_descr']);
	$objh->inventorymoving_source = trim($rs->fields['inventorymoving_source']);
	$objh->inventorymoving_createby = trim($rs->fields['inventorymoving_createby']);
	$objh->inventorymoving_createdate = trim($rs->fields['inventorymoving_createdate']);
	$objh->inventorymoving_modifyby = trim($rs->fields['inventorymoving_modifyby']);
	$objh->inventorymoving_modifydate = trim($rs->fields['inventorymoving_modifydate']);
	$objh->inventorymoving_sendby = trim($rs->fields['inventorymoving_sendby']);
	$objh->inventorymoving_senddate = trim($rs->fields['inventorymoving_senddate']);
	$objh->inventorymoving_receiveby = trim($rs->fields['inventorymoving_receiveby']);
	$objh->inventorymoving_receivedate = trim($rs->fields['inventorymoving_receivedate']);
	$objh->inventorymoving_postby = trim($rs->fields['inventorymoving_postby']);
	$objh->inventorymoving_postdate = trim($rs->fields['inventorymoving_postdate']);
	$objh->inventorymoving_isproposed = trim($rs->fields['inventorymoving_isproposed']);
	$objh->inventorymoving_issent = trim($rs->fields['inventorymoving_ispostedsend']);
	$objh->inventorymoving_isreceived = trim($rs->fields['inventorymoving_ispostedreceive']);
	$objh->inventorymoving_isposted = trim($rs->fields['inventorymoving_isposted']);
	$objh->inventorymovingtype_id = trim($rs->fields['inventorymovingtype_id']);
	$objh->region_id = trim($rs->fields['region_id_source']);
	$objh->branch_id_from = trim($rs->fields['branch_id_source']);
	$objh->branch_id_to = trim($rs->fields['branch_id_target']);
	$objh->iteminventorytype_id = trim($rs->fields['iteminventorytype_id']);
	$objh->iteminventorysubtype_id = trim($rs->fields['iteminventorysubtype_id']);
	$objh->rekanan_id = trim($rs->fields['rekanan_id']);
	$objh->transaction_id = trim($rs->fields['transaction_id']);
	$objh->channel_id = trim($rs->fields['channel_id']);
	$objh->ref_id = trim($rs->fields['ref_id']);
	$objh->rowid = trim($rs->fields['rowid']);







	$TOTAL_PROPOSED = 0;
	$TOTAL_SENT = 0;
	$TOTAL_RECEIVE = 0; 
	$VALUE_SENT = 0;
	$VALUE_RECEIVE = 0;
	$sql = "select * from transaksi_inventorymovingdetil where inventorymoving_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($arrdata); unset($arrdata_product); unset($arrdata_component);
	$arrdata = array();
	$arrdata_product = array();
	$arrdata_component = array();
	while (!$rs->EOF) {
		unset($obj);
		$obj->inventorymoving_id = trim($rs->fields['inventorymoving_id']);
		$obj->inventorymovingdetil_line = $rs->fields['inventorymovingdetil_line'];
		$obj->inventorymovingdetil_descr = str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['inventorymovingdetil_descr']);	
		$obj->iteminventory_id = $rs->fields['iteminventory_id'];	
		$obj->iteminventoryunit_id = $rs->fields['iteminventoryunit_id'];;
		$obj->inventorymovingdetil_idr = 1*$rs->fields['inventorymovingdetil_idr'];	
		
		if (substr($obj->inventorymoving_id,0,2)=='DO') {
			$mul = -1;
		} else {
			$mul = 1;
		}
		
		$obj->inventorymovingdetil_qtypropose = 1*$mul*$rs->fields['inventorymovingdetil_qtypropose'];			
		$obj->inventorymovingdetil_qtyinit = 1*$mul*$rs->fields['inventorymovingdetil_qtyinit'];	
		$obj->inventorymovingdetil_qty = 1*$mul*$rs->fields['inventorymovingdetil_qty'];	
		$obj->inventorymovingdetil_idrsubtotal = 1*$mul*$rs->fields['inventorymovingdetil_qty']*$rs->fields['inventorymovingdetil_idr'];
		$obj->ref_id = $rs->fields['ref_id'];
		$obj->ref_line = $rs->fields['ref_line'];

		/* cari dari iteminventory */
		$sql = sprintf("select iteminventory_article, iteminventory_material, iteminventory_color, iteminventory_size, region_id from master_iteminventory where iteminventory_id='%s'", $obj->iteminventory_id);
		$rsItem = $conn->Execute($sql);
		$obj->inventorymovingdetil_article = $rsItem->fields['iteminventory_article'];
		$obj->inventorymovingdetil_mat = $rsItem->fields['iteminventory_material'];
		$col = $rsItem->fields['iteminventory_color'];
		$size = $rsItem->fields['iteminventory_size'];
		$item_region_id = $rsItem->fields['region_id'];
		unset($rsItem);
		
		/* cari region induk */
		$sql = sprintf("select region_path from master_region where region_id='%s'", $item_region_id);
		$rsRegion = $conn->Execute($sql);
		$region_id = substr($rsRegion->fields['region_path'], 0, 5);
		unset($rsRegion);
		
	
		/* terjemahkan color */
		$sql = sprintf("select iteminventorycolor_name from master_iteminventorycolor where region_id='%s' and iteminventorycolor_id='%s'", $region_id, $col);
		$rsColor = $conn->Execute($sql);
		$obj->inventorymovingdetil_col = $rsColor->fields['iteminventorycolor_name'];
		unset($rsColor);


		/* terjemahkan size */
		$sql = sprintf("select iteminventorysize_name from master_iteminventorysize where region_id='%s' and iteminventorysize_id='%s'", $region_id, $size);
		$rsSize = $conn->Execute($sql);
		$obj->inventorymovingdetil_size = $rsSize->fields['iteminventorysize_name'];
		unset($rsSize);
		
		
		/* cek pakah RV, klo iya, munculin nilai PO nya berapa */
		if ($objh->inventorymovingtype_id=='RV') {
			$sql = sprintf("select ocdetil_qty from transaksi_ocdetil where oc_id='%s' and ocdetil_line=%s ", $obj->ref_id, ($obj->ref_line?$obj->ref_line:0));
			$rsOcdetil = $conn->Execute($sql);		
			$obj->ocdetil_qty = 1*$rsOcdetil->fields['ocdetil_qty'];
			
			/* munculkan jumlah yang sudah diterima */
			$sql = "select inventorymovingdetil_qty=SUM(inventorymovingdetil_qty) 
				    from transaksi_inventorymoving A inner join transaksi_inventorymovingdetil B
		            on A.inventorymoving_id = B.inventorymoving_id AND B.ref_id = '".$obj->ref_id."' and B.ref_line=".($obj->ref_line?$obj->ref_line:0)." ";
		    $rsI = $conn->Execute($sql);
		    if ($objh->inventorymoving_isreceived) {
				$obj->ocdetil_qtyreceived = (1*$rsI->fields['inventorymovingdetil_qty']);        
		    } else {
				$obj->ocdetil_qtyreceived = (1*$rsI->fields['inventorymovingdetil_qty']) - $obj->inventorymovingdetil_qty;        
			}
			
		}

	
		$obj->rowid = $rs->fields['rowid'];	
		
		
		if ($objh->inventorymovingtype_id=='AS') {
			if ($obj->inventorymovingdetil_qty > 0) {
				$arrdata_product[] = $obj;
			} else {
				$obj->inventorymovingdetil_qtypropose = -1*$obj->inventorymovingdetil_qtypropose;
				$obj->inventorymovingdetil_qtyinit = -1*$obj->inventorymovingdetil_qtyinit;
				$obj->inventorymovingdetil_qty = -1*$obj->inventorymovingdetil_qty;	
				$arrdata_component[] = $obj;
			}
		} else {
			$arrdata[] = $obj;
		}		



		$VALUE_SENT += ($obj->inventorymovingdetil_qtyinit * $obj->inventorymovingdetil_idr);
		$VALUE_RECEIVE += ($obj->inventorymovingdetil_qty * $obj->inventorymovingdetil_idr);
		$TOTAL_PROPOSED += $obj->inventorymovingdetil_qtypropose;
		$TOTAL_SENT += $obj->inventorymovingdetil_qtyinit;
		$TOTAL_RECEIVE += $obj->inventorymovingdetil_qty;
		
		$rs->MoveNext();
	}

	if ($objh->inventorymovingtype_id=='AS') {
		$data[0]['D']['DetilProduct'] = $arrdata_product;
		$data[0]['D']['DetilComponent'] = $arrdata_component;	
	} else {
		$data[0]['D']['DetilItem'] = $arrdata;
	}


	$objh->inventorymoving_valuesent = $VALUE_SENT; //trim($rs->fields['inventorymoving_valuesent']);
	$objh->inventorymoving_valuereceived = $VALUE_RECEIVE; //trim($rs->fields['inventorymoving_valuereceived']);
	$objh->inventorymoving_valuelost = abs($VALUE_SENT-$VALUE_RECEIVE); //trim($rs->fields['inventorymoving_valuelost']);
	$objh->inventorymoving_totalproposed = $TOTAL_PROPOSED;//trim($rs->fields['inventorymoving_totalsent']);
	$objh->inventorymoving_totalsent = $TOTAL_SENT;//trim($rs->fields['inventorymoving_totalsent']);
	$objh->inventorymoving_totalreceived = $TOTAL_RECEIVE; //trim($rs->fields['inventorymoving_totalreceived']);
	$objh->inventorymoving_totalbalance = abs($TOTAL_SENT-$TOTAL_RECEIVE); //trim($rs->fields['inventorymoving_totalbalance']);

	/* select rekanan name */
	$sql = sprintf("select rekanan_name from master_rekanan where rekanan_id='%s'", $objh->rekanan_id);
	$rs = $conn->Execute($sql);
	$objh->rekanan_name = trim($rs->fields['rekanan_name']);

	/* select oc descr */
	$sql = sprintf("select oc_descr from transaksi_oc where oc_id='%s'", $objh->ref_id);
	$rs = $conn->Execute($sql);
	$objh->ref_descr = trim($rs->fields['oc_descr']);

	$data[0]['H'] = $objh;
	








/* ====================================================================================================== */



	$sql = "select * from transaksi_inventorymovingdetilex where inventorymoving_id='$id'";
	$rs  = $conn->Execute($sql);
	unset($arrdata);
	$arrdata = array();
	while (!$rs->EOF) {
		unset($obj);
	
		$obj->inventorymoving_id = trim($rs->fields['inventorymoving_id']);
		$obj->inventorymovingdetilex_line = $rs->fields['inventorymovingdetilex_line'];
		$obj->inventorymovingdetilex_factorycode = $rs->fields['inventorymovingdetilex_factorycode'];	
		$obj->inventorymovingdetilex_descr = $rs->fields['inventorymovingdetilex_descr'];
		$obj->inventorymovingdetilex_article = $rs->fields['inventorymovingdetilex_article'];
		$obj->inventorymovingdetilex_material = $rs->fields['inventorymovingdetilex_material'];
		$obj->inventorymovingdetilex_color = $rs->fields['inventorymovingdetilex_color'];
		$obj->inventorymovingdetilex_size = $rs->fields['inventorymovingdetilex_size'];
		$obj->inventorymovingdetilex_qty = 1*$rs->fields['inventorymovingdetilex_qty'];
		$obj->inventorymovingdetilex_priceidr = 1*$rs->fields['inventorymovingdetilex_priceidr'];
		$obj->iteminventory_id = $rs->fields['iteminventory_id'];	
		$obj->ref_id = $rs->fields['ref_id'];	
		$obj->rowid = $rs->fields['rowid'];	
		$arrdata[] = $obj;
		$rs->MoveNext();
	}
	$data[0]['D']['DetilException'] = $arrdata;
	





$sql = "select * from transaksi_inventorymovingprop where inventorymoving_id='$id'";
$rs  = $conn->Execute($sql);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);

	$obj->prop_id = trim($rs->fields['inventorymoving_id']);
	$obj->prop_line = $rs->fields['inventorymovingprop_line'];
	$obj->prop_name = $rs->fields['prop_name'];
	$obj->prop_descr = $rs->fields['prop_descr'];
	$obj->prop_value = $rs->fields['prop_value'];
	$obj->rowid = $rs->fields['rowid'];	

	$arrdata[] = $obj;
	$rs->MoveNext();
}
$data[0]['D']['Prop'] = $arrdata;


$sql = "select * from transaksi_inventorymovinglog where inventorymoving_id='$id'";
$rs  = $conn->Execute($sql);
unset($arrdata);
$arrdata = array();
while (!$rs->EOF) {
	unset($obj);
	$obj->log_id = trim($rs->fields['inventorymoving_id']);
	$obj->log_line = $rs->fields['inventorymovinglog_line'];
	$obj->log_date = '11/11/2009'; //SQLUTIL::SQLDateParseToStringdate(trim($rs->fields['log_date']));
	$obj->log_action = $rs->fields['log_action'];
	$obj->log_descr = $rs->fields['log_descr'];
	$obj->log_table = $rs->fields['log_table'];
	$obj->log_lastvalue = $rs->fields['log_lastvalue'];
	$obj->log_username = $rs->fields['log_username'];
	$obj->rowid = $rs->fields['rowid'];
	$arrdata[] = $obj;
	$rs->MoveNext();
}
$data[0]['D']['Log'] = $arrdata;




$objResult = new WebResultObject("objResult");
$objResult->totalCount = 1;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>