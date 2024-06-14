<?php

	$__CONF['H']['TABLE_NAME'] 					= 'transaksi_heorder';
	$__CONF['H']['PRIMARY_KEY'] 				= 'heorder_id';
	$__CONF['H']['CREATEBY'] 					= 'heorder_createby';
	$__CONF['H']['CREATEDATE'] 					= 'heorder_createdate';
	$__CONF['H']['MODIFYBY'] 					= 'heorder_modifyby';
	$__CONF['H']['MODIFYDATE'] 					= 'heorder_modifydate';


	$__CONF['D']['DetilItem']['TABLE_NAME']		= "transaksi_heorderdetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']	= "heorder_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']	= "heorderdetil_line";
	
	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>