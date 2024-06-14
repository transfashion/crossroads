<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {

			unset($obj);
			//$obj->batch_id  		= $__POSTDATA->H->region_id;
			$obj->batch_date  		= $__POSTDATA->H->batch_date;
			$obj->batch_descr  		= $__POSTDATA->H->batch_descr;
			$obj->region_id  		= $__POSTDATA->H->region_id;
			$obj->batch_isposted  	= 1*$__POSTDATA->H->batch_isposted;
			$obj->batch_isean  		= 1; //1*$__POSTDATA->H->batch_isean;


			$_ID_GENERATOR_ARGS = array(
				'prefix' => 'PB',
				'region_id' => $__POSTDATA->H->region_id,
				'branch_id' =>'0000100'
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