<?

		$DETIL_NAME = "Detil";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);

				$obj->bon_id				= $__ID ;
				$obj->bondetil_line			= $arrDetilData[$i]->bondetil_line;
				$obj->bondetil_item			= $arrDetilData[$i]->bondetil_item;
				$obj->bondetil_descr		= $arrDetilData[$i]->bondetil_descr;
				$obj->bondetil_article		= $arrDetilData[$i]->bondetil_article;
				$obj->bondetil_color		= $arrDetilData[$i]->bondetil_color;
				$obj->bondetil_size			= $arrDetilData[$i]->bondetil_size;
				$obj->bondetil_material		= $arrDetilData[$i]->bondetil_material;
				$obj->bondetil_pricegross	= $arrDetilData[$i]->bondetil_pricegross;
				$obj->bondetil_discount		= $arrDetilData[$i]->bondetil_discount;
				$obj->bondetil_pricenet		= $arrDetilData[$i]->bondetil_pricenet;
				$obj->bondetil_qty			= $arrDetilData[$i]->bondetil_qty;
				$obj->bondetil_subtotal		= $arrDetilData[$i]->bondetil_subtotal;
				$obj->region_id				= $arrDetilData[$i]->region_id;
				$obj->promo_line			= $arrDetilData[$i]->promo_line;
				$obj->promorule_id			= $arrDetilData[$i]->promorule_id;
				$obj->CODESYN				= $__SYNID ;
				$obj->PRODATE				= $arrDetilData[$i]->PRODATE;
				$obj->branch_id				= $arrDetilData[$i]->branch_id;


				$sql = "SELECT * FROM transaksi_postempdetil WHERE bon_id='$__ID' AND bondetil_line='".$obj->bondetil_line."'";
				$rs = $conn->Execute($sql);
				if ($rs->recordCount()) {
					$criteria = "bon_id='".$obj->bon_id."' AND bondetil_line='".$obj->bondetil_line."'";
					$sql = SQLUTIL::SQL_UpdateFromObject("transaksi_postempdetil", $obj, $criteria);
				} else {
					$sql = SQLUTIL::SQL_InsertFromObject("transaksi_postempdetil", $obj);
				}

				try {
					
					$conn->Execute($sql);
					
				} catch (Exception $e) {
					//$conn->RollbackTrans();
					$msg = $e->getMessage();
					$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
					
					/* tulis ke text */
					//$file = dirname(__FILE__)."/error.log.txt";
					//$fpe = fopen($file, "w");
					//fputs($fpe, "Error di DETIL\n".$msg);
					//fclose($fpe);
				}

				$sql = "";				

				
				
				
				/*
				if ($arrDetilData[$i]->__ROWSTATE=='NEW') {
				} else {
				}
				*/
				
				
			}
		}



?>