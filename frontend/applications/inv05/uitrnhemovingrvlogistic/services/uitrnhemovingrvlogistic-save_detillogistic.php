<?php

		$DETIL_NAME = "DetilLogisticCost";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
			
				$obj->hemovinglogisticcost_line = $arrDetilData[$i]->hemovinglogisticcost_line;
				$obj->hemovinglogisticcost_descr = $arrDetilData[$i]->hemovinglogisticcost_descr;
				$obj->hemovinglogisticcost_amount = $arrDetilData[$i]->hemovinglogisticcost_amount;
				$obj->hemovinglogisticcost_rate = $arrDetilData[$i]->hemovinglogisticcost_rate;
				$obj->acclogisticcost_id = $arrDetilData[$i]->acclogisticcost_id;
				$obj->acclogisticcosttmp_code = $arrDetilData[$i]->acclogisticcosttmp_code;
				$obj->rekanan_id = $arrDetilData[$i]->rekanan_id;
				$obj->currency_id = $arrDetilData[$i]->currency_id;
			
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>