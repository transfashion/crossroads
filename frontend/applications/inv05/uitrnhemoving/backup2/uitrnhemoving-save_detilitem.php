<?php

		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->hemovingdetil_line = $arrDetilData[$i]->hemovingdetil_line;
				$obj->heinv_id = $arrDetilData[$i]->heinv_id;
				$obj->heinv_art = $arrDetilData[$i]->heinv_art;
				$obj->heinv_mat = $arrDetilData[$i]->heinv_mat;
				$obj->heinv_col = $arrDetilData[$i]->heinv_col;
				$obj->heinv_name = $arrDetilData[$i]->heinv_name;
				$obj->heinv_price = $arrDetilData[$i]->heinv_price;
				$obj->heinv_disc = $arrDetilData[$i]->heinv_disc;
				$obj->heinv_box = "". is_object($arrDetilData[$i]->heinv_box) ? "" : $arrDetilData[$i]->heinv_box;
				$obj->heinv_invoiceqty = $arrDetilData[$i]->heinv_invoiceqty;	
				$obj->heinv_invoiceid = $arrDetilData[$i]->heinv_invoiceid;		
				$obj->A01 = $arrDetilData[$i]->A01;
				$obj->A02 = $arrDetilData[$i]->A02;
				$obj->A03 = $arrDetilData[$i]->A03;
				$obj->A04 = $arrDetilData[$i]->A04;
				$obj->A05 = $arrDetilData[$i]->A05;
				$obj->A06 = $arrDetilData[$i]->A06;
				$obj->A07 = $arrDetilData[$i]->A07;
				$obj->A08 = $arrDetilData[$i]->A08;
				$obj->A09 = $arrDetilData[$i]->A09;
				$obj->A10 = $arrDetilData[$i]->A10;
				$obj->A11 = $arrDetilData[$i]->A11;
				$obj->A12 = $arrDetilData[$i]->A12;
				$obj->A13 = $arrDetilData[$i]->A13;
				$obj->A14 = $arrDetilData[$i]->A14;
				$obj->A15 = $arrDetilData[$i]->A15;
				$obj->A16 = $arrDetilData[$i]->A16;
				$obj->A17 = $arrDetilData[$i]->A17;
				$obj->A18 = $arrDetilData[$i]->A18;
				$obj->A19 = $arrDetilData[$i]->A19;
				$obj->A20 = $arrDetilData[$i]->A20;
				$obj->A21 = $arrDetilData[$i]->A21;
				$obj->A22 = $arrDetilData[$i]->A22;
				$obj->A23 = $arrDetilData[$i]->A23;
				$obj->A24 = $arrDetilData[$i]->A24;
				$obj->A25 = $arrDetilData[$i]->A25;
				$obj->B01 = $arrDetilData[$i]->B01;
				$obj->B02 = $arrDetilData[$i]->B02;
				$obj->B03 = $arrDetilData[$i]->B03;
				$obj->B04 = $arrDetilData[$i]->B04;
				$obj->B05 = $arrDetilData[$i]->B05;
				$obj->B06 = $arrDetilData[$i]->B06;
				$obj->B07 = $arrDetilData[$i]->B07;
				$obj->B08 = $arrDetilData[$i]->B08;
				$obj->B09 = $arrDetilData[$i]->B09;
				$obj->B10 = $arrDetilData[$i]->B10;
				$obj->B11 = $arrDetilData[$i]->B11;
				$obj->B12 = $arrDetilData[$i]->B12;
				$obj->B13 = $arrDetilData[$i]->B13;
				$obj->B14 = $arrDetilData[$i]->B14;
				$obj->B15 = $arrDetilData[$i]->B15;
				$obj->B16 = $arrDetilData[$i]->B16;
				$obj->B17 = $arrDetilData[$i]->B17;
				$obj->B18 = $arrDetilData[$i]->B18;
				$obj->B19 = $arrDetilData[$i]->B19;
				$obj->B20 = $arrDetilData[$i]->B20;
				$obj->B21 = $arrDetilData[$i]->B21;
				$obj->B22 = $arrDetilData[$i]->B22;
				$obj->B23 = $arrDetilData[$i]->B23;
				$obj->B24 = $arrDetilData[$i]->B24;
				$obj->B25 = $arrDetilData[$i]->B25;
				$obj->C01 = $arrDetilData[$i]->C01;
				$obj->C02 = $arrDetilData[$i]->C02;
				$obj->C03 = $arrDetilData[$i]->C03;
				$obj->C04 = $arrDetilData[$i]->C04;
				$obj->C05 = $arrDetilData[$i]->C05;
				$obj->C06 = $arrDetilData[$i]->C06;
				$obj->C07 = $arrDetilData[$i]->C07;
				$obj->C08 = $arrDetilData[$i]->C08;
				$obj->C09 = $arrDetilData[$i]->C09;
				$obj->C10 = $arrDetilData[$i]->C10;
				$obj->C11 = $arrDetilData[$i]->C11;
				$obj->C12 = $arrDetilData[$i]->C12;
				$obj->C13 = $arrDetilData[$i]->C13;
				$obj->C14 = $arrDetilData[$i]->C14;
				$obj->C15 = $arrDetilData[$i]->C15;
				$obj->C16 = $arrDetilData[$i]->C16;
				$obj->C17 = $arrDetilData[$i]->C17;
				$obj->C18 = $arrDetilData[$i]->C18;
				$obj->C19 = $arrDetilData[$i]->C19;
				$obj->C20 = $arrDetilData[$i]->C20;
				$obj->C21 = $arrDetilData[$i]->C21;
				$obj->C22 = $arrDetilData[$i]->C22;
				$obj->C23 = $arrDetilData[$i]->C23;
				$obj->C24 = $arrDetilData[$i]->C24;
				$obj->C25 = $arrDetilData[$i]->C25;

				$obj->ref_id = $arrDetilData[$i]->ref_id;
				$obj->ref_line = $arrDetilData[$i]->ref_line;
				
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>