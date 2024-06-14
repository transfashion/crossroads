<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->inventorymoving_id			= $__POSTDATA->H->inventorymoving_id;
			$obj->inventorymoving_descr			= $__POSTDATA->H->inventorymoving_descr;
	        $obj->inventorymoving_source		= $__POSTDATA->H->inventorymoving_source ;
	        $obj->inventorymovingtype_id		= $__POSTDATA->H->inventorymovingtype_id ;
	        $obj->ref_id						= $__POSTDATA->H->ref_id ;
	        $obj->rekanan_id					= $__POSTDATA->H->rekanan_id ;
	        $obj->region_id_source				= $__POSTDATA->H->region_id ;
	        $obj->region_id_target				= $__POSTDATA->H->region_id ;
	        $obj->branch_id_source				= $__POSTDATA->H->branch_id_from ;
	        $obj->branch_id_target				= $__POSTDATA->H->branch_id_to ;
	        $obj->iteminventorytype_id			= $__POSTDATA->H->iteminventorytype_id ;
	        $obj->iteminventorysubtype_id		= $__POSTDATA->H->iteminventorysubtype_id ;
	        $obj->channel_id					= $__POSTDATA->H->channel_id ;

			$_ID_GENERATOR_ARGS = array(
				'prefix' => $obj->inventorymovingtype_id,
				'region_id' => $__POSTDATA->H->region_id,
				'branch_id' => $__POSTDATA->H->branch_id_from
			);
			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';
		}
		

		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;
		
			$prefix = $_ID_GENERATOR_ARGS['prefix'];
			$region_id = $_ID_GENERATOR_ARGS['region_id'];
			$branch_id = $_ID_GENERATOR_ARGS['branch_id'];
			$channel_number = '05';
			
			/*
			$sql  = "SELECT *, year=YEAR(lastdate) FROM sequencer_inventorymoving ";
			$sql .= "WHERE ";
			$sql .= "type='$prefix' AND region_id='$region_id' and branch_id='$branch_id' AND channel_id='$channel_number'";
			$rs = $conn->Execute($sql);
			
			if (!$rs->recordCount()) {
				return $prefix.time();
			} else {
				if (date("Y")!=$rs->fields['year']) {
					$lastid = 0;
				} else {
				
				}
			}
			*/
			
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