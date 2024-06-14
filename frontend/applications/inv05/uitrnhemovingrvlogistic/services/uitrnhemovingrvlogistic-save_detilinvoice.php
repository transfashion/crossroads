<?php

		$DETIL_NAME = "DetilInvoice";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->hemovinginvoice_line = $arrDetilData[$i]->hemovinginvoice_line;
				$obj->hemovinginvoice_ref = $arrDetilData[$i]->hemovinginvoice_ref;
				$obj->hemovinginvoice_date = $arrDetilData[$i]->hemovinginvoice_date;
				$obj->hemovinginvoice_datedue = $arrDetilData[$i]->hemovinginvoice_datedue;
				$obj->hemovinginvoice_descr = $arrDetilData[$i]->hemovinginvoice_descr;
				$obj->hemovinginvoice_qty = $arrDetilData[$i]->hemovinginvoice_qty;
				$obj->hemovinginvoice_foreign = $arrDetilData[$i]->hemovinginvoice_foreign;
				$obj->hemovinginvoice_foreignrate = $arrDetilData[$i]->hemovinginvoice_foreignrate;
				$obj->hemovinginvoice_idr = (float) $obj->hemovinginvoice_foreignrate * (float) $obj->hemovinginvoice_foreign;
				$obj->currency_id = $arrDetilData[$i]->currency_id;
				
				
				/*
				$obj->hemovinglogisticcost_line = $arrDetilData[$i]->hemovinglogisticcost_line;
				$obj->hemovinglogisticcost_descr = $arrDetilData[$i]->hemovinglogisticcost_descr;
				$obj->hemovinglogisticcost_amount = $arrDetilData[$i]->hemovinglogisticcost_amount;
				$obj->hemovinglogisticcost_rate = $arrDetilData[$i]->hemovinglogisticcost_rate;
				$obj->acclogisticcost_id = $arrDetilData[$i]->acclogisticcost_id;
				$obj->acclogisticcosttmp_code = $arrDetilData[$i]->acclogisticcosttmp_code;
				$obj->rekanan_id = $arrDetilData[$i]->rekanan_id;
				$obj->currency_id = $arrDetilData[$i]->currency_id;
				*/
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>