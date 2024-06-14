<?

		/* Update Header */
		if (!empty($__POSTDATA->H)) {			

			unset($obj);
			$obj->bon_id = $__ID ;
			$obj->machine_id = $__POSTDATA->H->machine_id ;
			$obj->region_id = $__POSTDATA->H->region_id ;
			$obj->branch_id = $__POSTDATA->H->branch_id ;
			$obj->events = $__POSTDATA->H->events ? $__POSTDATA->H->events : "DEFPOS";
			$obj->bon_date = $__POSTDATA->H->bon_date ;
			$obj->operator = $__POSTDATA->H->operator ;
			$obj->vip_id = $__POSTDATA->H->vip_id ;
			$obj->pospayment_id = $__POSTDATA->H->pospayment_id ;
			$obj->paym1_cardno = $__POSTDATA->H->paym1_cardno ;
			$obj->paym1_holder = $__POSTDATA->H->paym1_holder ;
			$obj->paym1_EDC = $__POSTDATA->H->paym1_EDC ;
			$obj->paym1_value = $__POSTDATA->H->paym1_value ;
			$obj->paym2_cardno = $__POSTDATA->H->paym2_cardno ;
			$obj->paym2_holder = $__POSTDATA->H->paym2_holder ;
			$obj->paym2_EDC = $__POSTDATA->H->paym2_EDC ;
			$obj->paym2_value = $__POSTDATA->H->paym2_value ;
			$obj->paym3_cash = 1*$__POSTDATA->H->paym3_cash ;
			$obj->paym3_changedue = 1*$__POSTDATA->H->paym3_changedue ;
			$obj->paym_subtotal = $__POSTDATA->H->paym_subtotal ;
			$obj->paym_disc = $__POSTDATA->H->paym_disc ;
			$obj->paym_discvalue = $__POSTDATA->H->paym_discvalue ;
			$obj->paym_subtotalnett = $__POSTDATA->H->paym_subtotalnett ;
			$obj->paym_nettsales = $__POSTDATA->H->paym_nettsales ;
			$obj->paym_ppn = $__POSTDATA->H->paym_ppn ;
			$obj->paym_ppnvalue = $__POSTDATA->H->paym_ppnvalue ;
			$obj->paym_gross = $__POSTDATA->H->paym_gross ;
			$obj->voucher_id = $__POSTDATA->H->voucher_id ;
			$obj->voucher_amount = $__POSTDATA->H->voucher_amount ;
			$obj->redim_id = $__POSTDATA->H->redim_id ;
			$obj->redim_amount = $__POSTDATA->H->redim_amount ;
			$obj->paym_nett = $__POSTDATA->H->paym_nett ;
			$obj->paym_otherincome = $__POSTDATA->H->paym_otherincome ;
			$obj->pos_npwp = $__POSTDATA->H->pos_npwp ;
			$obj->pos_void_from = $__POSTDATA->H->pos_void_from ;
			$obj->pos_isposted = $__POSTDATA->H->pos_isposted ;
			$obj->pos_isvoid = $__POSTDATA->H->pos_isvoid ;
			$obj->CODESYN = $__SYNID;
			$obj->PRODATE = $__POSTDATA->H->PRODATE ;
			$obj->rowid = $__ROWID;

			
		
			if ($obj->bon_id == 'SL/05/COR/MKS-CAND1/01/100800142') 
			{
				$obj->paym1_EDC = FLAZZ01;
			}
		
			

			if ($__POSTDATA->H->__ROWSTATE=='NEW') {
				$sql = SQLUTIL::SQL_InsertFromObject("transaksi_postemp", $obj);
			} else {
				$criteria = "bon_id='$__ID'";
				$sql = SQLUTIL::SQL_UpdateFromObject("transaksi_postemp", $obj, $criteria);
			}
			
			
			try {
 
							$conn->Execute($sql); 	
 		 
				
			} catch (Exception $e) {
				//$conn->RollbackTrans();
				$msg = $e->getMessage();
				$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
				
				/* tulis ke text */
				$file = dirname(__FILE__)."/error.log.txt";
				$fpe = fopen($file, "w");
				fputs($fpe, "Error di Header\n".$msg);
				fclose($fpe);
			}
	
	
			$sql = "";
			
			$__RESULT[0]->H = $obj;
			
		}	

?>