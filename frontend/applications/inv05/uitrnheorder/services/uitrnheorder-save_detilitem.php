<?php

		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->heinv_id 		= $arrDetilData[$i]->heinv_id;
				$obj->heinv_art 	= $arrDetilData[$i]->heinv_art;
				$obj->heinv_mat 	= $arrDetilData[$i]->heinv_mat;
				$obj->heinv_col 	= $arrDetilData[$i]->heinv_col;
				$obj->heinv_name 	= $arrDetilData[$i]->heinv_name;
				$obj->heinv_price 	= $arrDetilData[$i]->heinv_price;
				
				$obj->C01 	= $arrDetilData[$i]->C01;
				$obj->C02 	= $arrDetilData[$i]->C02;
				$obj->C03 	= $arrDetilData[$i]->C03;
				$obj->C04 	= $arrDetilData[$i]->C04;
				$obj->C05 	= $arrDetilData[$i]->C05;
				$obj->C06 	= $arrDetilData[$i]->C06;
				$obj->C07 	= $arrDetilData[$i]->C07;
				$obj->C08 	= $arrDetilData[$i]->C08;
				$obj->C09 	= $arrDetilData[$i]->C09;
				$obj->C10 	= $arrDetilData[$i]->C10;
				$obj->C11 	= $arrDetilData[$i]->C11;
				$obj->C12 	= $arrDetilData[$i]->C12;
				$obj->C13 	= $arrDetilData[$i]->C13;
				$obj->C14 	= $arrDetilData[$i]->C14;
				$obj->C15 	= $arrDetilData[$i]->C15;
				$obj->C16 	= $arrDetilData[$i]->C16;
				$obj->C17 	= $arrDetilData[$i]->C17;
				$obj->C18 	= $arrDetilData[$i]->C18;
				$obj->C19 	= $arrDetilData[$i]->C19;
				$obj->C20 	= $arrDetilData[$i]->C20;
				$obj->C21 	= $arrDetilData[$i]->C21;
				$obj->C22 	= $arrDetilData[$i]->C22;
				$obj->C23 	= $arrDetilData[$i]->C23;
				$obj->C24 	= $arrDetilData[$i]->C24;
				$obj->C25 	= $arrDetilData[$i]->C25;
				
				$_MODIFIED = true; 
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>