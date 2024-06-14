<?php

		$DETIL_NAME = "DetilBank";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {

				unset($obj);
				$obj->customerbank_name		= $arrDetilData[$i]->customerbank_name;
				$obj->customerbank_account	= $arrDetilData[$i]->customerbank_account;
				$obj->bank_id				= $arrDetilData[$i]->bank_id;
			
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';		
				
			}
			
		}


?>