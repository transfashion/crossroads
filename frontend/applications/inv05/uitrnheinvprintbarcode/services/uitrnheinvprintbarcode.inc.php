<?php

	$__CONF['H']['TABLE_NAME'] 						= 'transaksi_heinvprintbarcode';
	$__CONF['H']['PRIMARY_KEY'] 					= 'batch_id';
	$__CONF['H']['CREATEBY'] 						= 'batch_createby';
	$__CONF['H']['CREATEDATE'] 						= 'batch_createdate';
	$__CONF['H']['MODIFYBY'] 						= 'batch_modifyby';
	$__CONF['H']['MODIFYDATE'] 						= 'batch_modifydate';

	$__CONF['D']['DetilItem']['TABLE_NAME']			= "transaksi_heinvprintbarcodedetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']		= "batch_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']		= "batchdetil_line";
	

	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>