<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   luki.widodorn    created date 26/10/2011 10:14
Asset Request
Filename: uitrnAssetReq-save_detilitem.php
*/


		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				$obj->assetrequest_id = $arrDetilData[$i]->assetrequest_id;
				$obj->assetrequestdetil_line = $arrDetilData[$i]->assetrequestdetil_line;
				$obj->assetclass_id = $arrDetilData[$i]->assetclass_id;
				$obj->assetclass_name = $arrDetilData[$i]->assetclass_name;
				$obj->assetrequestdetil_descr = $arrDetilData[$i]->assetrequestdetil_descr;
				$obj->assetrequestdetil_qty = $arrDetilData[$i]->assetrequestdetil_qty;
				$obj->region_id = $arrDetilData[$i]->region_id;
				$obj->branch_id = $arrDetilData[$i]->branch_id;
				$obj->strukturunit_id = $arrDetilData[$i]->strukturunit_id;
				$obj->owner_strukturunit_id = $arrDetilData[$i]->owner_strukturunit_id;
				$obj->assetrequestdetil_isclosed = $arrDetilData[$i]->assetrequestdetil_isclosed;
				$obj->project_id = $arrDetilData[$i]->project_id;
				$obj->rowid = $arrDetilData[$i]->rowid;
				
				$_MODIFIED = true; 
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>