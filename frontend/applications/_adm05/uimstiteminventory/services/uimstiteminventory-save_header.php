<?php
 
            
		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->iteminventory_id					= $__POSTDATA->H->iteminventory_id;
			$obj->iteminventory_name				= $__POSTDATA->H->iteminventory_name;
	        $obj->iteminventory_factorycode			= $__POSTDATA->H->iteminventory_factorycode ;
	        $obj->iteminventory_article				= $__POSTDATA->H->iteminventory_article ;
	        $obj->iteminventory_material			= $__POSTDATA->H->iteminventory_material ;
	        $obj->iteminventory_color				= $__POSTDATA->H->iteminventory_color ;
	        $obj->iteminventory_size				= $__POSTDATA->H->iteminventory_size ;
	        $obj->iteminventory_descr				= $__POSTDATA->H->iteminventory_descr ;
	        $obj->iteminventory_isconsumable		= $__POSTDATA->H->iteminventory_isconsumable ;
	        $obj->iteminventory_isassembly			= $__POSTDATA->H->iteminventory_isassembly ;
	        $obj->iteminventory_isconsinyasi		= $__POSTDATA->H->iteminventory_isconsinyasi ;
	        $obj->iteminventory_isempty				= $__POSTDATA->H->iteminventory_isempty ;
	        $obj->iteminventory_isdisabled			= $__POSTDATA->H->iteminventory_isdisabled ;
	        $obj->iteminventory_isbufferenable		= $__POSTDATA->H->iteminventory_isbufferenable ;
   	        $obj->iteminventory_buypricedefault		= $__POSTDATA->H->iteminventory_buypricedefault ;
   	        $obj->iteminventory_sellpricedefault	= $__POSTDATA->H->iteminventory_sellpricedefault ;   	        
   	        $obj->iteminventory_discountdefault		= $__POSTDATA->H->iteminventory_discountdefault ;   	        
   	        $obj->iteminventory_minsupplies			= $__POSTDATA->H->iteminventory_minsupplies ;   	        
   	        $obj->iteminventory_maxsupplies			= $__POSTDATA->H->iteminventory_maxsupplies ;   	
   	        $obj->iteminventory_format				= $__POSTDATA->H->iteminventory_format ;   				           
   	        $obj->iteminventorytype_id				= $__POSTDATA->H->iteminventorytype_id ;   				           
   	        $obj->iteminventorysubtype_id			= $__POSTDATA->H->iteminventorysubtype_id ;
   	        $obj->iteminventorygroup_id				= $__POSTDATA->H->iteminventorygroup_id ;
   	        $obj->iteminventorysubgroup_id			= $__POSTDATA->H->iteminventorysubgroup_id ;
   	        $obj->iteminventoryunittype_id			= $__POSTDATA->H->iteminventoryunittype_id ;   	        
   	        $obj->iteminventoryunit_id				= $__POSTDATA->H->iteminventoryunit_id ;   	        
   	        $obj->region_id							= $__POSTDATA->H->region_id ;   	        
   	        $obj->season_id							= $__POSTDATA->H->season_id ;   	        
   	        $obj->channel_id						= $__POSTDATA->H->channel_id ;   	        
	        

			$_ID_GENERATOR_ARGS = array(
				'prefix' =>'TS'
			);
			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';

		}
			

		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;
		
			$prefix = $_ID_GENERATOR_ARGS['prefix'];
			$channel_number = '05';
			 
			$sql  =  "DECLARE @id as varchar(50);";
			$sql .=  "EXEC cp_sequencer_item 'MGP','$prefix', @id OUTPUT ";
			$rs   = $conn->Execute($sql);
			
			$id = $rs->fields['id'];
			
			if ($id) {
				return $id;
			} else {
				return $prefix.time();
			}
			
			
		}		
	
?>