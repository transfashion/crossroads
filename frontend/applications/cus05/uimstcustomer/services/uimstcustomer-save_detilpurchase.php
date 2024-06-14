<?php

 	
		$DETIL_NAME = "DetilPurchase";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {

				unset($obj);
				//$obj->customer_id					= $__ID;
				//$obj->customerpurchase_line			= $arrDetilData[$i]->customerpurchase_line;
				$obj->customerpurchase_date			= $arrDetilData[$i]->customerpurchase_date;
				$obj->customerpurchase_descr		= $arrDetilData[$i]->customerpurchase_descr;
				$obj->customerpurchase_qty			= $arrDetilData[$i]->customerpurchase_qty;
				$obj->customerpurchase_value		= $arrDetilData[$i]->customerpurchase_value;
				$obj->region_id						= $arrDetilData[$i]->region_id;
				$obj->branch_id						= $arrDetilData[$i]->branch_id;
		 
				 require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
				
			}
		}


?>