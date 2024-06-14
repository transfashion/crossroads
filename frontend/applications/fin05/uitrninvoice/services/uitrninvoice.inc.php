<?php

	$__CONF['H']['TABLE_NAME'] 					= 'transaksi_invoice';
	$__CONF['H']['PRIMARY_KEY'] 				= 'invoice_id';
	$__CONF['H']['CREATEBY'] 					= 'invoice_createby';
	$__CONF['H']['CREATEDATE'] 					= 'invoice_createdate';
	$__CONF['H']['MODIFYBY'] 					= 'invoice_modifyby';
	$__CONF['H']['MODIFYDATE'] 					= 'invoice_modifydate';


	$__CONF['D']['DetilItem']['TABLE_NAME']		= "transaksi_invoicedetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']	= "invoice_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']	= "invoicedetil_line";
	
	
	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_invoiceprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "invoice_id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "invoiceprop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_invoicelog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "invoice_id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "invoicelog_line";	



?>