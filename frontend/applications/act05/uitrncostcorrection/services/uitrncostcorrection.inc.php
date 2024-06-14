<?php

	$__CONF['H']['TABLE_NAME'] 						= 'transaksi_jurnal';
	$__CONF['H']['PRIMARY_KEY'] 					= 'jurnal_id';
	$__CONF['H']['CREATEBY'] 						= 'jurnal_createby';
	$__CONF['H']['CREATEDATE'] 						= 'jurnal_createdate';
	$__CONF['H']['MODIFYBY'] 						= 'jurnal_modifyby';
	$__CONF['H']['MODIFYDATE'] 						= 'jurnal_modifydate';

	$__CONF['D']['DetilItem']['TABLE_NAME']			= "transaksi_jurnaldetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']		= "jurnal_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']		= "jurnaldetil_line";
	
	


	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>