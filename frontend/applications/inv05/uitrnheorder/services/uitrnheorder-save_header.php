<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->heorder_idext 		= $__POSTDATA->H->heorder_idext;
			$obj->heorder_source 		= $__POSTDATA->H->heorder_source;
			$obj->heorder_date 			= $__POSTDATA->H->heorder_date;
			$obj->heorder_dateexp 		= $__POSTDATA->H->heorder_dateexp;
			$obj->heorder_descr 		= $__POSTDATA->H->heorder_descr;
			$obj->region_id 			= $__POSTDATA->H->region_id;
			$obj->rekanan_id 			= $__POSTDATA->H->rekanan_id;
			$obj->season_id 			= $__POSTDATA->H->season_id;
			$obj->currency_id 			= $__POSTDATA->H->currency_id;
			
			$_MODIFIED = true; 
			$_ID_GENERATOR_ARGS = array(
				'prefix' 	=> 'PO',
				'region_id' => $__POSTDATA->H->region_id,
				'branch_id' => '0'
			);
			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';
		}
		
		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;		
			$prefix = $_ID_GENERATOR_ARGS['prefix'];
			$region_id = $_ID_GENERATOR_ARGS['region_id'];
			$branch_id = $_ID_GENERATOR_ARGS['branch_id'];
			$channel_number = '05';
		
			$sql  =  "DECLARE @id as varchar(50);";
			$sql .=  "EXEC inv03_sequencer '$prefix', 'MGP', '$region_id', '$branch_id', @id OUTPUT ";
			$rs   = $conn->Execute($sql);
			$id = $rs->fields['id'];
			if ($id) {
				return $id;
			} else {
				return $prefix.time();
			}
		}		
	
?>