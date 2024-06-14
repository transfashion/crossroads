<?php

		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->heinvitem_size 	= $arrDetilData[$i]->heinvitem_size;
				$obj->heinvitem_barcode = $arrDetilData[$i]->heinvitem_barcode;
				
				$_MODIFIED = true; 
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>