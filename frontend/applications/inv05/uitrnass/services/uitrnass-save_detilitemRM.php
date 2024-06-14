<?php

		$DETIL_NAME = "DetilItemRM";
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
				$obj->B01 = $arrDetilData[$i]->A01;
				$obj->B02 = $arrDetilData[$i]->A02;
				$obj->B03 = $arrDetilData[$i]->A03;
				$obj->B04 = $arrDetilData[$i]->A04;
				$obj->B05 = $arrDetilData[$i]->A05;
				$obj->B06 = $arrDetilData[$i]->A06;
				$obj->B07 = $arrDetilData[$i]->A07;
				$obj->B08 = $arrDetilData[$i]->A08;
				$obj->B09 = $arrDetilData[$i]->A09;
				$obj->B10 = $arrDetilData[$i]->A10;
				$obj->B11 = $arrDetilData[$i]->A11;
				$obj->B12 = $arrDetilData[$i]->A12;
				$obj->B13 = $arrDetilData[$i]->A13;
				$obj->B14 = $arrDetilData[$i]->A14;
				$obj->B15 = $arrDetilData[$i]->A15;
				$obj->B16 = $arrDetilData[$i]->A16;
				$obj->B17 = $arrDetilData[$i]->A17;
				$obj->B18 = $arrDetilData[$i]->A18;
				$obj->B19 = $arrDetilData[$i]->A19;
				$obj->B20 = $arrDetilData[$i]->A20;
				$obj->B21 = $arrDetilData[$i]->A21;
				$obj->B22 = $arrDetilData[$i]->A22;
				$obj->B23 = $arrDetilData[$i]->A23;
				$obj->B24 = $arrDetilData[$i]->A24;
				$obj->B25 = $arrDetilData[$i]->A25;
				$obj->C01 = $arrDetilData[$i]->A01;
				$obj->C02 = $arrDetilData[$i]->A02;
				$obj->C03 = $arrDetilData[$i]->A03;
				$obj->C04 = $arrDetilData[$i]->A04;
				$obj->C05 = $arrDetilData[$i]->A05;
				$obj->C06 = $arrDetilData[$i]->A06;
				$obj->C07 = $arrDetilData[$i]->A07;
				$obj->C08 = $arrDetilData[$i]->A08;
				$obj->C09 = $arrDetilData[$i]->A09;
				$obj->C10 = $arrDetilData[$i]->A10;
				$obj->C11 = $arrDetilData[$i]->A11;
				$obj->C12 = $arrDetilData[$i]->A12;
				$obj->C13 = $arrDetilData[$i]->A13;
				$obj->C14 = $arrDetilData[$i]->A14;
				$obj->C15 = $arrDetilData[$i]->A15;
				$obj->C16 = $arrDetilData[$i]->A16;
				$obj->C17 = $arrDetilData[$i]->A17;
				$obj->C18 = $arrDetilData[$i]->A18;
				$obj->C19 = $arrDetilData[$i]->A19;
				$obj->C20 = $arrDetilData[$i]->A20;
				$obj->C21 = $arrDetilData[$i]->A21;
				$obj->C22 = $arrDetilData[$i]->A22;
				$obj->C23 = $arrDetilData[$i]->A23;
				$obj->C24 = $arrDetilData[$i]->A24;
				$obj->C25 = $arrDetilData[$i]->A25;

				$obj->ref_id = $arrDetilData[$i]->ref_id;
				$obj->ref_line = $arrDetilData[$i]->ref_line;
				
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>