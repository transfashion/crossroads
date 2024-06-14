<?php

		$DETIL_NAME = "DetilContact";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {

				unset($obj);
				$obj->customercontact_name		= $arrDetilData[$i]->customercontact_name;
				$obj->customercontact_address	= $arrDetilData[$i]->customercontact_address;
				$obj->customercontact_phone		= $arrDetilData[$i]->customercontact_phone;
				$obj->customercontact_email		= $arrDetilData[$i]->customercontact_email;
				$obj->customercontact_position	= $arrDetilData[$i]->customercontact_position;
				$obj->customercontact_primary	= $arrDetilData[$i]->customercontact_primary;
			
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
				
			}
		}


?>