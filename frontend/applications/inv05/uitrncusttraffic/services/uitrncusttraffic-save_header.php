<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   dwi.atnorn    created date 01/07/2011 11:11
customer traffic
Filename: uitrncusttraffic-save_header.php
*/

	

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->custtraffic_date = $__POSTDATA->H->custtraffic_date;
			$obj->custtraffic_isposted = $__POSTDATA->H->custtraffic_isposted;	
			$obj->custtraffic_modifyby = $__POSTDATA->H->custtraffic_modifyby;
			$obj->custtraffic_modifydate = $__POSTDATA->H->custtraffic_modifydate;
			$obj->custtraffic_createby = $__POSTDATA->H->custtraffic_createby;
			$obj->custtraffic_createdate = $__POSTDATA->H->custtraffic_createdate;
			$obj->region_id = $__POSTDATA->H->region_id;
			$obj->branch_id = $__POSTDATA->H->branch_id;
		
			$_MODIFIED = true; 
			$_ID_GENERATOR_ARGS = array(
				'prefix' 	=> ''
			);
			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';
		}
		
		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;		
			$prefix = $_ID_GENERATOR_ARGS['prefix'];

			if ($id) {
				return $id;
			} else {
				return $prefix.time();
			}
		}		
	

?>