<?php

	$__CONF['H']['TABLE_NAME'] 					= 'transaksi_opname';
	$__CONF['H']['PRIMARY_KEY'] 				= 'opname_id';
	$__CONF['H']['CREATEBY'] 					= 'opname_createby';
	$__CONF['H']['CREATEDATE'] 					= 'opname_createdate';
	$__CONF['H']['MODIFYBY'] 					= 'opname_modifyby';
	$__CONF['H']['MODIFYDATE'] 					= 'opname_modifydate';
	
	$__CONF['D']['DetilItem']['TABLE_NAME']			= "transaksi_opnamedetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']		= "opname_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']		= "opnamedetil_line";


	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>