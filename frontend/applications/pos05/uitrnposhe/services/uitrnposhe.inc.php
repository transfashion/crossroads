<?php

	
	$__CONF['H']['TABLE_NAME'] 					= 'transaksi_hepos';
	$__CONF['H']['PRIMARY_KEY'] 				= 'bon_id';
	$__CONF['H']['CREATEBY'] 					= 'bon_createby';
	$__CONF['H']['CREATEDATE'] 					= 'bon_createdate';
	$__CONF['H']['MODIFYBY'] 					= 'bon_modifyby';
	$__CONF['H']['MODIFYDATE'] 					= 'bon_modifydate';


	$__CONF['D']['DetilItem']['TABLE_NAME']		= "transaksi_heposdetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']	= "bon_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']	= "bondetil_line";

	$__CONF['D']['DetilPayment']['TABLE_NAME']		= "transaksi_hepospayment";
	$__CONF['D']['DetilPayment']['PRIMARY_KEY1']	= "bon_id";
	$__CONF['D']['DetilPayment']['PRIMARY_KEY2']	= "payment_line";
	
	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	

	

?>