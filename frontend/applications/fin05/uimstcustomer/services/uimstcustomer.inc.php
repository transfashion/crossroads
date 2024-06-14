<?php

	$__CONF['H']['TABLE_NAME'] 					= 'master_customer';
	$__CONF['H']['PRIMARY_KEY'] 				= 'customer_id';
	$__CONF['H']['CREATEBY'] 					= 'customer_createby';
	$__CONF['H']['CREATEDATE'] 					= 'customer_createdate';
	$__CONF['H']['MODIFYBY'] 					= 'customer_modifyby';
	$__CONF['H']['MODIFYDATE'] 					= 'customer_modifydate';

	$__CONF['D']['DetilRegion']['TABLE_NAME']	= "master_customerregion";
	$__CONF['D']['DetilRegion']['PRIMARY_KEY1']	= "customer_id";
	$__CONF['D']['DetilRegion']['PRIMARY_KEY2']	= "region_id";	

	$__CONF['D']['DetilBank']['TABLE_NAME']		= "master_customerbank";
	$__CONF['D']['DetilBank']['PRIMARY_KEY1']	= "customer_id";
	$__CONF['D']['DetilBank']['PRIMARY_KEY2']	= "customerbank_line";
	
	$__CONF['D']['DetilContact']['TABLE_NAME']	= "master_customercontact";
	$__CONF['D']['DetilContact']['PRIMARY_KEY1']= "customer_id";
	$__CONF['D']['DetilContact']['PRIMARY_KEY2']= "customercontact_line";	
	
	$__CONF['D']['Prop']['TABLE_NAME']			= "master_customerprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "prop_id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "master_customerlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "log_id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>