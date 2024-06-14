<?php

		$DETIL_NAME = "transaksi_custtrafficdetil";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->custtrafficdetil_waktu = $arrDetilData[$i]->custtrafficdetil_waktu;
				$obj->custtrafficdetil_W = $arrDetilData[$i]->custtrafficdetil_W;
				$obj->custtrafficdetil_I = $arrDetilData[$i]->custtrafficdetil_I;
				$obj->custtrafficdetil_P = $arrDetilData[$i]->custtrafficdetil_P;
								
				
								
				$_MODIFIED = true; 
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>