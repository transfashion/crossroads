<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			//$obj->heinv_art		 	= $__POSTDATA->H->heinv_art;
			//$obj->heinv_mat 		= $__POSTDATA->H->heinv_mat;
			//$obj->heinv_col 		= $__POSTDATA->H->heinv_col;
			//$obj->heinv_name 		= $__POSTDATA->H->heinv_name;
			//$obj->heinv_descr 		= $__POSTDATA->H->heinv_descr;
			$obj->heinv_gtype		= $__POSTDATA->H->heinv_gtype;
			$obj->heinv_isdisabled	= $__POSTDATA->H->heinv_isdisabled;
			//$obj->heinv_price01 	= $__POSTDATA->H->heinv_price01;
			//$obj->heinv_pricedisc01 = $__POSTDATA->H->heinv_pricedisc01;
			$obj->heinvgro_id 		= $__POSTDATA->H->heinvgro_id;
			$obj->heinvctg_id 		= $__POSTDATA->H->heinvctg_id;
			$obj->season_id 		= $__POSTDATA->H->season_id;
			//$obj->region_id 		= $__POSTDATA->H->region_id;
						

			$_MODIFIED = true; 
			$_ID_GENERATOR_ARGS = array(
				'prefix' 	=> 'TM'
			);
	
			
			/* CEK DATA */
			if ($__POSTDATA->H->__ROWSTATE=='NEW') {
				$sql = "SELECT * FROM master_heinv WHERE heinv_art='".$obj->heinv_art."' AND heinv_mat='".$obj->heinv_mat."' AND heinv_col='".$obj->heinv_col."' AND region_id='".$obj->region_id."'";
				$rs  = $conn->Execute($sql);
				if ($rs->recordCount()) throw new Exception("ART '".$obj->heinv_art."', MAT '".$obj->heinv_mat."', COL '".$obj->heinv_col."', region '".$obj->region_id."' Sudah ada dalam database. ");
			} else {
				$sql = "SELECT * FROM master_heinv WHERE heinv_art='".$obj->heinv_art."' AND heinv_mat='".$obj->heinv_mat."' AND heinv_col='".$obj->heinv_col."' AND region_id='".$obj->region_id."' AND heinv_id<>'".$__ID."'";
				$rs  = $conn->Execute($sql);
				if ($rs->recordCount()) throw new Exception("ART '".$obj->heinv_art."', MAT '".$obj->heinv_mat."', COL '".$obj->heinv_col."', region '".$obj->region_id."' Sudah ada dalam database. ");
			}
			
			$__SQL_DEBUG__ = false;
			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';
			
		}
		
		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;		
			$prefix = $_ID_GENERATOR_ARGS['prefix'];
			$sql = "DECLARE @id as nvarchar(13);
			        EXEC sp_sequencer_heinv '$prefix', @id;";
			$rs   = $conn->Execute($sql);
			$id = $rs->fields['id'];
			if ($id) {
				return $id;
			} else {
				return $prefix.time();
			}
		}		
	
?>