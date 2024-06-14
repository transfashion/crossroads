<?php

		$DETIL_NAME = "Prop";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {

				unset($obj);
				$obj->prop_name		= $arrDetilData[$i]->prop_name;
				$obj->prop_descr	= $arrDetilData[$i]->prop_descr;
				$obj->prop_value	= $arrDetilData[$i]->prop_value;
			
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';						

			}
		}

?>