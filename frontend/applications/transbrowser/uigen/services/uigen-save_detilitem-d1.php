<?php

		$DETIL_NAME = "Detil1";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);

				$obj->uigendetil_line = $arrDetilData[$i]->uigendetil_line;
				$obj->uigendetil_seq = $arrDetilData[$i]->uigendetil_seq;
				$obj->uigendetil_name = $arrDetilData[$i]->uigendetil_name;
				$obj->uigendetil_text = $arrDetilData[$i]->uigendetil_text;
				$obj->uigendetil_datatype = $arrDetilData[$i]->uigendetil_datatype;
				$obj->uigendetil_datalen = $arrDetilData[$i]->uigendetil_datalen;
				$obj->uigendetil_dataprec = $arrDetilData[$i]->uigendetil_dataprec;
				$obj->uigendetil_isgenerate = $arrDetilData[$i]->uigendetil_isgenerate;
				$obj->uigendetil_type = $arrDetilData[$i]->uigendetil_type;
				$obj->uigendetil_objectwidth = $arrDetilData[$i]->uigendetil_objectwidth;
				$obj->uigendetil_objectcolor = $arrDetilData[$i]->uigendetil_objectcolor;
				$obj->uigendetil_islisted = $arrDetilData[$i]->uigendetil_islisted;
				$obj->uigendetil_issearch = $arrDetilData[$i]->uigendetil_issearch;				
				$obj->uigendetil_isvisible = $arrDetilData[$i]->uigendetil_isvisible;
				$obj->uigendetil_isenabled = $arrDetilData[$i]->uigendetil_isenabled;
				$obj->uigendetil_textalign = $arrDetilData[$i]->uigendetil_textalign;

				$_MODIFIED = true; 
				require dirname(__FILE__).'/../../../../updatedefault-detil3.inc.php';				
			}
		}


?>