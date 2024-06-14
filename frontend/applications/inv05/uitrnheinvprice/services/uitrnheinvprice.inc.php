<?php

	$__CONF['H']['TABLE_NAME'] 						= 'transaksi_heinvprice';
	$__CONF['H']['PRIMARY_KEY'] 					= 'price_id';
	$__CONF['H']['CREATEBY'] 						= 'price_createby';
	$__CONF['H']['CREATEDATE'] 						= 'price_createdate';
	$__CONF['H']['MODIFYBY'] 						= 'price_modifyby';
	$__CONF['H']['MODIFYDATE'] 						= 'price_modifydate';

	$__CONF['D']['DetilItem']['TABLE_NAME']			= "transaksi_heinvpricedetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']		= "price_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']		= "pricedetil_line";
	

	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>