<?php
/*
Generated by TransBrowser Generator
*** Genearated by TransBrowser UI Application Generator --rn    created by   luki.widodorn    created date 26/10/2011 10:14
Asset Request
Filename: trnAssetReq-save_header.php
*/

	

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->assetrequest_id = $__POSTDATA->H->assetrequest_id;
			$obj->assetrequest_date = $__POSTDATA->H->assetrequest_date;
			$obj->assetrequest_isdisabled = $__POSTDATA->H->assetrequest_isdisabled;
			$obj->assetrequest_isposted = $__POSTDATA->H->assetrequest_isposted;
			$obj->assetrequest_issent = $__POSTDATA->H->assetrequest_issent;
			$obj->assetrequest_duedate = $__POSTDATA->H->assetrequest_duedate;
			$obj->assetrequest_descr = $__POSTDATA->H->assetrequest_descr;
			$obj->assetrequest_dept = $__POSTDATA->H->assetrequest_dept;
			$obj->assetrequest_createby = $__POSTDATA->H->assetrequest_createby;
			$obj->assetrequest_createdate = $__POSTDATA->H->assetrequest_createdate;
			$obj->assetrequest_modifyby = $__POSTDATA->H->assetrequest_modifyby;
			$obj->assetrequest_modifydate = $__POSTDATA->H->assetrequest_modifydate;
			$obj->region_id = $__POSTDATA->H->region_id;
			$obj->branch_id = $__POSTDATA->H->branch_id;
			$obj->strukturunit_id = $__POSTDATA->H->strukturunit_id;
			$obj->owner_strukturunit_id = $__POSTDATA->H->owner_strukturunit_id;
			$obj->project_id = $__POSTDATA->H->project_id;
			$obj->rowid = $__POSTDATA->H->rowid;
		
			
			$_MODIFIED = true; 
			$_ID_GENERATOR_ARGS = array(
				'prefix'  => 'PR',
				'channel_id' 	=> 'MGP',
				'owner_strukturunit_id'  => $__POSTDATA->H->owner_strukturunit_id
				
			);
			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';
		}

		function GenerateID($_ID_GENERATOR_ARGS) {
			global $conn;
			$prefix = $_ID_GENERATOR_ARGS['prefix'];		
			$channel_id = $_ID_GENERATOR_ARGS['channel_id'];
			$owner_strukturunit_id = $_ID_GENERATOR_ARGS['owner_strukturunit_id'];
			
			
			$sql  =		"DECLARE @id as nvarchar(50)";
			$sql .=  	"EXEC sp_sequencer_assettrans '$channel_id','$prefix','$owner_strukturunit_id',@id OUTPUT ";
			$rs   = 	$conn->Execute($sql);
			$id   =		$rs->fields['id'];



			if ($id) {
				return $id;
			} else {
				return $prefix.time();
			}
		}		
	

?>