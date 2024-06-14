<?php

		$DETIL_NAME = "DetilException";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {

				unset($obj);
				$obj->inventorymovingdetilex_factorycode= $arrDetilData[$i]->inventorymovingdetilex_factorycode;
				$obj->inventorymovingdetilex_descr		= $arrDetilData[$i]->inventorymovingdetilex_descr;
				$obj->inventorymovingdetilex_qty		= $arrDetilData[$i]->inventorymovingdetilex_qty;
				$obj->inventorymovingdetilex_priceidr	= $arrDetilData[$i]->inventorymovingdetilex_priceidr;
				$obj->inventorymovingdetilex_article	= $arrDetilData[$i]->inventorymovingdetilex_article;
				$obj->inventorymovingdetilex_material	= $arrDetilData[$i]->inventorymovingdetilex_material;
				$obj->inventorymovingdetilex_size		= $arrDetilData[$i]->inventorymovingdetilex_size;
				$obj->iteminventory_id		= $arrDetilData[$i]->iteminventory_id;
				
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
				
			}
		}


?>