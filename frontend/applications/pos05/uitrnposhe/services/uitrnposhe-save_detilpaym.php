<?php

		$DETIL_NAME = "DetilPayment";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->payment_line = $arrDetilData[$i]->payment_line;
				$obj->payment_cardnumber = $arrDetilData[$i]->payment_cardnumber;
				$obj->payment_cardholder = $arrDetilData[$i]->payment_cardholder;
				$obj->payment_mvalue = $arrDetilData[$i]->payment_mvalue;
				$obj->payment_mcash = $arrDetilData[$i]->payment_mcash;
				$obj->payment_installment = $arrDetilData[$i]->payment_installment;
				$obj->pospayment_id = $arrDetilData[$i]->pospayment_id;
				$obj->pospayment_name = $arrDetilData[$i]->pospayment_name;
				$obj->pospayment_bank = $arrDetilData[$i]->pospayment_bank;
				$obj->posedc_id = $arrDetilData[$i]->posedc_id;
				$obj->posedc_name = $arrDetilData[$i]->posedc_name;
							
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>