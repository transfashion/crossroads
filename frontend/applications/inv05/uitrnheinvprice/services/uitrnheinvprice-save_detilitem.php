<?php

		$sql = "select price_isnewitemprice from transaksi_heinvprice where price_id = '$__ID' ";
		$rsi = $conn->Execute($sql);
		$isnewitemprice = $rsi->fields['price_isnewitemprice'];


		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
			
				unset($obj);
				$obj->pricedetil_line = 1*$arrDetilData[$i]->pricedetil_line;
				$obj->heinv_id = trim($arrDetilData[$i]->heinv_id);			
				$obj->heinv_art = $arrDetilData[$i]->heinv_art;
				$obj->heinv_mat = $arrDetilData[$i]->heinv_mat;
				$obj->heinv_col = $arrDetilData[$i]->heinv_col;
				$obj->heinv_name = $arrDetilData[$i]->heinv_name;
				$obj->heinv_lastprice = 1*$arrDetilData[$i]->heinv_lastprice;
				$obj->heinv_lastdisc = 1*$arrDetilData[$i]->heinv_lastdisc;
				$obj->heinv_price01 = 1*$arrDetilData[$i]->heinv_price01;
				$obj->heinv_pricedisc01 = 1*$arrDetilData[$i]->heinv_pricedisc01;

				$obj->heinv_price_hk = 1*$arrDetilData[$i]->heinv_price_hk;
				$obj->heinv_price_sin = 1*$arrDetilData[$i]->heinv_price_sin;

				//$obj->heinv_isSP = 1*$arrDetilData[$i]->heinv_isSP;
				//$obj->heinv_isadjgross = 1*$arrDetilData[$i]->heinv_isadjgross;
				$obj->heinvctg_id = $arrDetilData[$i]->heinvctg_id;				
				$obj->heinvgro_id = $arrDetilData[$i]->heinvgro_id;
				$obj->ref_id = $arrDetilData[$i]->ref_id;
				$obj->ref_line = $arrDetilData[$i]->ref_line;


				$proposed_price = (float)$obj->heinv_price01;
				$proposed_disc = (float)$obj->heinv_pricedisc01;

				if ($isnewitemprice==0) {

					$sql = "
						select 
						A.heinv_price01,
						A.heinv_pricedisc01,
						(A.heinv_priceori + (
							SELECT ISNULL((SELECT TOP 1 heinvpriceadj_value
							FROM dbo.master_heinvpriceadj
							WHERE heinv_id = A.heinv_id AND CONVERT(varchar(10), heinvpriceadj_date, 120) <= CONVERT(varchar(10), GETDATE(), 120)
							ORDER BY heinvpriceadj_date DESC), 0))) as heinv_currentpricegross,
						
						heinv_lastcost AS heinv_cost,
						heinv_lastcost AS heinv_lastcost,
						ISNULL(B.[END], 0) as heinv_lastqty,
						ISNULL(B.age, 0) as heinv_age,
						A.heinvctg_id,
						A.heinvgro_id
						from
						master_heinv A left join cache_invsum B on A.heinv_id=B.heinv_id and B.BLOCK='BRAND'
						where
						A.heinv_id = '$obj->heinv_id'				
					";

					$rs = $conn->Execute($sql);
					if ($rs->recordCount()>0) {

						$obj->heinv_lastprice = (float)$rs->fields['heinv_price01'];
						$obj->heinv_lastdisc =(float)$rs->fields['heinv_pricedisc01'];
		
						$obj->heinv_currentpricegross = (float)$rs->fields['heinv_currentpricegross'];
						$obj->heinv_cost = (float)$rs->fields['heinv_cost'];
						$obj->heinv_lastcost = (float)$rs->fields['heinv_lastcost'];
						$obj->heinv_lastqty = (int)$rs->fields['heinv_lastqty'];
						$obj->heinv_age = (int)$rs->fields['heinv_age'];
						$obj->heinvctg_id = $rs->fields['heinvctg_id'];
						$obj->heinvgro_id = $rs->fields['heinvgro_id'];

						if ($proposed_price < $obj->heinv_currentpricegross) {
							if ($proposed_disc==0) {
								$obj->heinv_isSP = 1;
							} else {
								throw new Exception("Cek baris $obj->pricedetil_line, harga dasar yang diajukan kurang dari Gross, dengan tambahan discount.");
							}
						}

					} else {
						$obj->heinv_currentpricegross = 0;
						$obj->heinv_cost = 0;
						$obj->heinv_lastcost = 0;
						$obj->heinv_lastqty = 0;
						$obj->heinv_age = 0;
						$obj->heinvctg_id = '';
						$obj->heinvgro_id = '';					
					}

				} else {
				
					if ($proposed_disc > 0) {
						throw new Exception("Cek baris $obj->pricedetil_line, Pricing barang baru tidak boleh ada discount.");
					}
				}

				
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';	
			 		
			}
		}


?>