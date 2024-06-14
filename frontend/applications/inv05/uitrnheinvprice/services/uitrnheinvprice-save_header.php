<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
					
			unset($obj);
			//$obj->price_id  		= $__POSTDATA->H->price_id;
			$obj->region_id  		= $__POSTDATA->H->region_id;
			$obj->pricingtype_id  	= $__POSTDATA->H->pricingtype_id;
			$obj->price_startdate  	= $__POSTDATA->H->price_startdate;
			$obj->price_enddate  	= $__POSTDATA->H->price_enddate;
			$obj->price_descr  		= $__POSTDATA->H->price_descr;
			$obj->price_isposted  	= $__POSTDATA->H->price_isposted;
			$obj->price_isposted  	= $__POSTDATA->H->price_isposted;
			$obj->price_isgenerated	= $__POSTDATA->H->price_isgenerated;
            $obj->project_id    	= $__POSTDATA->H->project_id;
			$obj->ref_id  			= $__POSTDATA->H->ref_id;
			$_ID_GENERATOR_ARGS = array(
				'prefix' => 'PC',
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