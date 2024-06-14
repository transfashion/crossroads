<?php

		if ($__POSTDATA->H->iteminventorytype_id == 'TR') {
			if (!$__POSTDATA->H->branch_id_from || !$__POSTDATA->H->branch_id_to) {
				throw new Exception('Empty $__POSTDATA->H->branch_id_*');
			}
		}
		
		if ($__POSTDATA->H->iteminventorytype_id == 'RV') {
			if (!$__POSTDATA->H->region_id ) {
				throw new Exception('Empty $__POSTDATA->H->region_id*');
			}
		}		

		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {

				/*
				unset($obj);
				$obj->inventorymovingdetil_descr		= $arrDetilData[$i]->inventorymovingdetil_descr;
				$obj->inventorymovingdetil_qtypropose	= $arrDetilData[$i]->inventorymovingdetil_qtypropose;
				$obj->inventorymovingdetil_qtyinit		= $arrDetilData[$i]->inventorymovingdetil_qtyinit;
				$obj->inventorymovingdetil_qty			= $arrDetilData[$i]->inventorymovingdetil_qty;
				$obj->inventorymovingdetil_idr			= $arrDetilData[$i]->inventorymovingdetil_idr;
				
				$obj->iteminventorytype_id				= $__POSTDATA->H->iteminventorytype_id;
				$obj->iteminventorysubtype_id			= $__POSTDATA->H->iteminventorysubtype_id;
				$obj->region_id_source					= $__POSTDATA->H->region_id;
				$obj->region_id_target					= $__POSTDATA->H->region_id;
				$obj->branch_id_source					= $__POSTDATA->H->branch_id_from;
				$obj->branch_id_target					= $__POSTDATA->H->branch_id_to;
				
				$obj->ref_id			= $arrDetilData[$i]->ref_id;
				$obj->ref_line			= 1*$arrDetilData[$i]->ref_line ? $arrDetilData[$i]->ref_line : 0;
				$obj->iteminventory_id	= $arrDetilData[$i]->iteminventory_id;
			
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';
				*/	
				
				unset($obj);
				$obj->inventorymovingdetil_descr		= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $arrDetilData[$i]->inventorymovingdetil_descr);
				$obj->inventorymovingdetil_qtypropose	= $arrDetilData[$i]->inventorymovingdetil_qtypropose;
				$obj->inventorymovingdetil_qtyinit		= $arrDetilData[$i]->inventorymovingdetil_qtyinit;
				$obj->inventorymovingdetil_qty			= $arrDetilData[$i]->inventorymovingdetil_qty;
				$obj->inventorymovingdetil_idr			= $arrDetilData[$i]->inventorymovingdetil_idr;

				$obj->ref_id			= $arrDetilData[$i]->ref_id;
				$obj->ref_line			= 1*$arrDetilData[$i]->ref_line ? $arrDetilData[$i]->ref_line : 0;
				$obj->iteminventory_id	= $arrDetilData[$i]->iteminventory_id;
	
				$obj->branch_id_source					= $__POSTDATA->H->branch_id_from;
				$obj->branch_id_target					= $__POSTDATA->H->branch_id_to;

				/* ambil data dari master_iteminventory */
				$sql = "select * from master_iteminventory where iteminventory_id='".$obj->iteminventory_id."'";
				$rsI = $conn->Execute($sql);
				$obj->iteminventorytype_id 		= $rsI->fields['iteminventorytype_id'];
				$obj->iteminventorysubtype_id 	= $rsI->fields['iteminventorysubtype_id'];
				$obj->region_id_source			= $rsI->fields['region_id'];
				$obj->region_id_target			= $rsI->fields['region_id'];
				
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';									
				
			}
		}


?>