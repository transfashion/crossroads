<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --
    created by   dwi.atno
    created date 04/05/2011 15:56
inv tester
Filename: uimstctg-save_header.php
*/

	

		/* Update Header */
		if (!empty($__POSTDATA->H)) {


 	
			unset($obj);
			$obj->heinvctg_id = $__POSTDATA->H->heinvctg_id;	
			$obj->heinvctg_extctg = substr($__POSTDATA->H->heinvctg_id,5,3);
			$obj->heinvctg_extgro = substr($__POSTDATA->H->heinvctg_id,3,2);
			$obj->heinvctg_seqcode = $__POSTDATA->H->heinvctg_id;
			$obj->heinvctg_name = $__POSTDATA->H->heinvctg_name;
			$obj->heinvctg_namegroup = $__POSTDATA->H->heinvctg_namegroup;
			$obj->heinvctg_descr = $__POSTDATA->H->heinvctg_descr;
			$obj->heinvctg_class = $__POSTDATA->H->heinvctg_class;
			$obj->heinvctg_gender = $__POSTDATA->H->heinvctg_gender;
			$obj->heinvctg_sizetag = $__POSTDATA->H->heinvctg_sizetag;
			$obj->heinvctg_isdisabled = 1*$__POSTDATA->H->heinvctg_isdisabled;
			$obj->heinvlogisticgroup_id = $__POSTDATA->H->heinvlogisticgroup_id;
			/*
			$obj->heinvctg_createby = $__POSTDATA->H->heinvctg_createby;
			$obj->heinvctg_createdate = $__POSTDATA->H->heinvctg_createdate;
			$obj->heinvctg_modifyby = $__POSTDATA->H->heinvctg_modifyby;
			$obj->heinvctg_modifydate = $__POSTDATA->H->heinvctg_modifydate;
			*/
			$obj->heinvgro_id = $__POSTDATA->H->heinvgro_id;
			$obj->region_id = $__POSTDATA->H->region_id;
 
			$_MODIFIED = true; 
			$_ID_GENERATOR_ARGS = array(
				'prefix' 	=> ''
			);
			require dirname(__FILE__).'/../../../../updatedefault-headermanualid.inc.php';
		}
		
		
		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;		
			$prefix = $_ID_GENERATOR_ARGS['prefix'];

			if ($id) {
				return $id;
			} else {
				return  $__POSTDATA->H->heinvctg_id;;
			}
		}		


?>