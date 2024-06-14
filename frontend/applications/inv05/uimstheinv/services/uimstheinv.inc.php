<?php

	$__CONF['H']['TABLE_NAME'] 					= 'master_heinv';
	$__CONF['H']['PRIMARY_KEY'] 				= 'heinv_id';
	$__CONF['H']['CREATEBY'] 					= 'heinv_createby';
	$__CONF['H']['CREATEDATE'] 					= 'heinv_createdate';
	$__CONF['H']['MODIFYBY'] 					= 'heinv_modifyby';
	$__CONF['H']['MODIFYDATE'] 					= 'heinv_modifydate';


	$__CONF['D']['DetilItem']['TABLE_NAME']		= "master_heinvitem";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']	= "heinv_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']	= "heinvitem_line";
	
	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>