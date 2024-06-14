<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->hemoving_source  		= $__POSTDATA->H->hemoving_source;
			$obj->hemoving_date  		= $__POSTDATA->H->hemoving_date;
			$obj->hemoving_date_fr  	= $__POSTDATA->H->hemoving_date_fr;
			$obj->hemoving_date_to  	= $__POSTDATA->H->hemoving_date_to;
			$obj->hemoving_descr  		= $__POSTDATA->H->hemoving_descr;
			$obj->hemovingtype_id  		= $__POSTDATA->H->hemovingtype_id;
			$obj->region_id  			= $__POSTDATA->H->region_id;
			$obj->region_id_out  		= $__POSTDATA->H->region_id_out;
			$obj->branch_id_fr  		= $__POSTDATA->H->branch_id_fr;
			$obj->branch_id_to  		= $__POSTDATA->H->branch_id_to;
			$obj->convert_fr  			= $__POSTDATA->H->convert_fr;
			$obj->convert_to  			= $__POSTDATA->H->convert_to;
			$obj->rekanan_id  			= $__POSTDATA->H->rekanan_id;
			$obj->currency_id  			= $__POSTDATA->H->currency_id;
			$obj->currency_rate  		= $__POSTDATA->H->currency_rate;
			$obj->disc_rate  			= $__POSTDATA->H->disc_rate;
			$obj->invoice_id  			=$__POSTDATA->H->invoice_id;
			$obj->season_id  			= $__POSTDATA->H->season_id;
			$obj->ref_id  				= $__POSTDATA->H->ref_id;
			
			switch ($obj->hemovingtype_id) {
				case '' :
					$branch_id = $obj->branch_id_to;
					break;
				default :
					$branch_id = $obj->branch_id_to;
				
			} 
			
			
			$_ID_GENERATOR_ARGS = array(
				'prefix' => $obj->hemovingtype_id,
				'region_id' => $__POSTDATA->H->region_id,
				'branch_id' => $branch_id
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