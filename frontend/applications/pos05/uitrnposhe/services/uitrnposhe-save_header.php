<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->bon_idext				= $__POSTDATA->H->bon_idext;
			$obj->bon_date				= $__POSTDATA->H->bon_date;
			$obj->bon_msubtotal			= $__POSTDATA->H->bon_msubtotal;
			$obj->bon_msubtvoucher		= $__POSTDATA->H->bon_msubtvoucher;
			$obj->bon_msubtdiscadd		= $__POSTDATA->H->bon_msubtdiscadd;
			$obj->bon_msubtredeem		= $__POSTDATA->H->bon_msubtredeem;
			$obj->bon_msubtracttotal	= $__POSTDATA->H->bon_msubtracttotal;
			$obj->bon_msubtotaltobedisc	= $__POSTDATA->H->bon_msubtotaltobedisc;
			$obj->bon_mdiscpaympercent	= $__POSTDATA->H->bon_mdiscpaympercent;
			$obj->bon_mdiscpayment		= $__POSTDATA->H->bon_mdiscpayment;
			$obj->bon_mtotal			= $__POSTDATA->H->bon_mtotal;
			$obj->bon_mpayment			= $__POSTDATA->H->bon_mpayment;
			$obj->bon_mrefund			= $__POSTDATA->H->bon_mrefund;
			$obj->bon_msalegross		= $__POSTDATA->H->bon_msalegross;
			$obj->bon_msaletax			= $__POSTDATA->H->bon_msaletax;
			$obj->bon_msalenet			= $__POSTDATA->H->bon_msalenet;
			$obj->bon_itemqty			= $__POSTDATA->H->bon_itemqty;
			$obj->salesperson_id		= $__POSTDATA->H->salesperson_id;
			$obj->salesperson_name		= $__POSTDATA->H->salesperson_name;			
			
	
			$_ID_GENERATOR_ARGS = array(
				'prefix' => "SL",
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
		
			/*
			$sql  =  "DECLARE @id as varchar(50);";
			$sql .=  "EXEC inv03_sequencer '$prefix', 'MGP', '$region_id', '$branch_id', @id OUTPUT ";
			$rs   = $conn->Execute($sql);
			$id = $rs->fields['id'];
			if ($id) {
				return $id;
			} else {
				return $prefix.time();
			}
			*/
			
		}
		
		

	
?>