<?php

		$DETIL_NAME = "Prop";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->prop_line 	= $arrDetilData[$i]->prop_line;
				$obj->prop_number 	= $arrDetilData[$i]->prop_number;
				$obj->prop_name 	= $arrDetilData[$i]->prop_name;
				$obj->prop_value 	= $arrDetilData[$i]->prop_value;
				$obj->prop_descr 	= $arrDetilData[$i]->prop_descr;
			
				$_MODIFIED = true; 
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>