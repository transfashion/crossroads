<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->uigen_name			= $__POSTDATA->H->uigen_name;
			$obj->uigen_text			= $__POSTDATA->H->uigen_text;
			$obj->uigen_descr			= $__POSTDATA->H->uigen_descr;
			$obj->uigen_type			= $__POSTDATA->H->uigen_type;
			$obj->uigen_header			= $__POSTDATA->H->uigen_header;
			$obj->uigen_namespace		= $__POSTDATA->H->uigen_namespace;
			$obj->uigen_objectname		= $__POSTDATA->H->uigen_objectname;
			$obj->uigen_dll				= $__POSTDATA->H->uigen_dll;
			$obj->uigen_issingleinstance= $__POSTDATA->H->uigen_issingleinstance;
			$obj->uigen_islocaldb		= $__POSTDATA->H->uigen_islocaldb;
			$obj->uigen_wsns			= $__POSTDATA->H->uigen_wsns;
			$obj->uigen_wsobject		= $__POSTDATA->H->uigen_wsobject;
			$obj->uigen_dataheadertable	= $__POSTDATA->H->uigen_dataheadertable;
			$obj->uigen_dataheaderfpk	= $__POSTDATA->H->uigen_dataheaderfpk;
			$obj->uigen_dataheaderfcb	= $__POSTDATA->H->uigen_dataheaderfcb;
			$obj->uigen_dataheaderfcd	= $__POSTDATA->H->uigen_dataheaderfcd;
			$obj->uigen_dataheaderfmb	= $__POSTDATA->H->uigen_dataheaderfmb;
			$obj->uigen_dataheaderfmd	= $__POSTDATA->H->uigen_dataheaderfmd;
			$obj->uigen_datadetil1use	= $__POSTDATA->H->uigen_datadetil1use;
			$obj->uigen_datadetil1name	= $__POSTDATA->H->uigen_datadetil1name;
			$obj->uigen_datadetil1table	= $__POSTDATA->H->uigen_datadetil1table;
			$obj->uigen_datadetil1fpk1	= $__POSTDATA->H->uigen_datadetil1fpk1;
			$obj->uigen_datadetil1fpk2	= $__POSTDATA->H->uigen_datadetil1fpk2;
			$obj->uigen_datadetil1text	= $__POSTDATA->H->uigen_datadetil1text;
			$obj->uigen_datadetil2use	= $__POSTDATA->H->uigen_datadetil2use;
			$obj->uigen_datadetil2name	= $__POSTDATA->H->uigen_datadetil2name;
			$obj->uigen_datadetil2table	= $__POSTDATA->H->uigen_datadetil2table;
			$obj->uigen_datadetil2fpk1	= $__POSTDATA->H->uigen_datadetil2fpk1;
			$obj->uigen_datadetil2fpk2	= $__POSTDATA->H->uigen_datadetil2fpk2;
			$obj->uigen_datadetil2text	= $__POSTDATA->H->uigen_datadetil2text;
			$obj->uigen_datadetil3use	= $__POSTDATA->H->uigen_datadetil3use;
			$obj->uigen_datadetil3name	= $__POSTDATA->H->uigen_datadetil3name;
			$obj->uigen_datadetil3table	= $__POSTDATA->H->uigen_datadetil3table;
			$obj->uigen_datadetil3fpk1	= $__POSTDATA->H->uigen_datadetil3fpk1;
			$obj->uigen_datadetil3fpk2	= $__POSTDATA->H->uigen_datadetil3fpk2;
			$obj->uigen_datadetil3text	= $__POSTDATA->H->uigen_datadetil3text;
			$obj->uigen_datadetil4use	= $__POSTDATA->H->uigen_datadetil4use;
			$obj->uigen_datadetil4name	= $__POSTDATA->H->uigen_datadetil4name;
			$obj->uigen_datadetil4table	= $__POSTDATA->H->uigen_datadetil4table;
			$obj->uigen_datadetil4fpk1	= $__POSTDATA->H->uigen_datadetil4fpk1;
			$obj->uigen_datadetil4fpk2	= $__POSTDATA->H->uigen_datadetil4fpk2;
			$obj->uigen_datadetil4text	= $__POSTDATA->H->uigen_datadetil4text;
			$obj->uigen_datadetil5use	= $__POSTDATA->H->uigen_datadetil5use;
			$obj->uigen_datadetil5name	= $__POSTDATA->H->uigen_datadetil5name;
			$obj->uigen_datadetil5table	= $__POSTDATA->H->uigen_datadetil5table;
			$obj->uigen_datadetil5fpk1	= $__POSTDATA->H->uigen_datadetil5fpk1;
			$obj->uigen_datadetil5fpk2	= $__POSTDATA->H->uigen_datadetil5fpk2;
			$obj->uigen_datadetil5text	= $__POSTDATA->H->uigen_datadetil5text;

			
			$_MODIFIED = true; 
			$_ID_GENERATOR_ARGS = array(
				'prefix' 	=> 'APP.',
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

			return $prefix.time();

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