<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   dwi.atnorn    created date 06/05/2011 15:25
asset tester
Filename: uimstasset-save_header.php
*/

	

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
				
			unset($obj);
			$obj->asset_bookdate = $__POSTDATA->H->asset_bookdate;
			$obj->asset_name = $__POSTDATA->H->asset_name;
			$obj->asset_descr = $__POSTDATA->H->asset_descr;
			$obj->asset_notes = $__POSTDATA->H->asset_notes;
			$obj->asset_merk = $__POSTDATA->H->asset_merk;
			$obj->asset_type = $__POSTDATA->H->asset_type;
			$obj->asset_sn = $__POSTDATA->H->asset_sn;
			$obj->asset_location = $__POSTDATA->H->asset_location;
			$obj->asset_pic = $__POSTDATA->H->asset_pic;
			$obj->asset_isparent = $__POSTDATA->H->asset_isparent;
			$obj->asset_owner_id = $__POSTDATA->H->asset_owner_id;
			$obj->asset_ownership = $__POSTDATA->H->asset_ownership;
			$obj->asset_movingtype = $__POSTDATA->H->asset_movingtype;
			$obj->asset_status = $__POSTDATA->H->asset_status;
			$obj->asset_iswritenoff = $__POSTDATA->H->asset_iswritenoff;

		/*	$obj->asset_createby = $__POSTDATA->H->asset_createby;
			$obj->asset_createdate = $__POSTDATA->H->asset_createdate;
			$obj->asset_modifyby =  $__POSTDATA->H->asset_modifyby;
			$obj->asset_modifydate = $__POSTDATA->H->asset_modifydate;*/

			

			$obj->asset_writenoffby = $__POSTDATA->H->asset_writenoffby;
		//	$obj->asset_writenoffdate = $__POSTDATA->H->asset_writenoffdate;
			$obj->asset_writenoffref = $__POSTDATA->H->asset_writenoffref;
			$obj->assetclass_id = $__POSTDATA->H->assetclass_id;
			$obj->assetorder_id = $__POSTDATA->H->assetorder_id;
			$obj->assetorder_line = $__POSTDATA->H->assetorder_line;
			$obj->assetrv_id = $__POSTDATA->H->assetrv_id;
			$obj->assetrv_line = $__POSTDATA->H->assetrv_line;
			$obj->zone_id = $__POSTDATA->H->zone_id;
			$obj->strukturunit_id = $__POSTDATA->H->strukturunit_id;
			$obj->branch_id = $__POSTDATA->H->branch_id;
			$obj->region_id = $__POSTDATA->H->region_id;
		
 
	
			$_MODIFIED = true; 
			$_ID_GENERATOR_ARGS = array(
				'assetclass_id' 	=> $__POSTDATA->H->assetclass_id,
				'strukturunit_id' => $__POSTDATA->H->strukturunit_id
			);

			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';
		}
		
		//	print 'xx';
		
		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;		
			$assetclass_id = $_ID_GENERATOR_ARGS['assetclass_id'];
			$strukturunit_id = $_ID_GENERATOR_ARGS['strukturunit_id'];
			
		
			$sql  =  "DECLARE @id as varchar(50)";
			$sql .=  "EXEC sp_sequencer_asset '$assetclass_id', '$strukturunit_id', @id OUTPUT ";
			$rs   = $conn->Execute($sql);
			$id = $rs->fields['id'];
			
		
			
			if ($id) {
				return $id;
				 
			} else {
				return $prefix.time();
			}
		}		
	

?>