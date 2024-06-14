<?php

		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->bondetil_line = $arrDetilData[$i]->bondetil_line;
				$obj->bondetil_art = $arrDetilData[$i]->bondetil_art;
				$obj->bondetil_mat = $arrDetilData[$i]->bondetil_mat;
				$obj->bondetil_col = $arrDetilData[$i]->bondetil_col;
				$obj->bondetil_size = $arrDetilData[$i]->bondetil_size;
				$obj->bondetil_descr = $arrDetilData[$i]->bondetil_descr;
				$obj->bondetil_qty = $arrDetilData[$i]->bondetil_qty;
				$obj->bondetil_mpricegross = $arrDetilData[$i]->bondetil_mpricegross;
				$obj->bondetil_mdiscpstd01 = $arrDetilData[$i]->bondetil_mdiscpstd01;
				$obj->bondetil_mdiscrstd01 = $arrDetilData[$i]->bondetil_mdiscrstd01;
				$obj->bondetil_mpricenettstd01 = $arrDetilData[$i]->bondetil_mpricenettstd01;
				$obj->bondetil_mdiscpvou01 = $arrDetilData[$i]->bondetil_mdiscpvou01;
				$obj->bondetil_mdiscrvou01 = $arrDetilData[$i]->bondetil_mdiscrvou01;
				$obj->bondetil_mpricecettvou01 = $arrDetilData[$i]->bondetil_mpricecettvou01;
				$obj->bondetil_mpricenett = $arrDetilData[$i]->bondetil_mpricenett;
				$obj->bondetil_msubtotal = $arrDetilData[$i]->bondetil_msubtotal;
				$obj->heinv_id = $arrDetilData[$i]->heinv_id;
				$obj->heinvitem_id = $arrDetilData[$i]->heinvitem_id;
				$obj->colname = $arrDetilData[$i]->colname;
		
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>