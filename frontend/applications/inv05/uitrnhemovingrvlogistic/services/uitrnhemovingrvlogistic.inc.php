<?php

	$__CONF['H']['TABLE_NAME'] 						= 'transaksi_hemoving';
	$__CONF['H']['PRIMARY_KEY'] 					= 'hemoving_id';
	$__CONF['H']['CREATEBY'] 						= 'hemoving_createby';
	$__CONF['H']['CREATEDATE'] 						= 'hemoving_createdate';
	$__CONF['H']['MODIFYBY'] 						= 'hemoving_modifyby';
	$__CONF['H']['MODIFYDATE'] 						= 'hemoving_modifydate';

	$__CONF['D']['DetilItem']['TABLE_NAME']			= "transaksi_hemovingdetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']		= "hemoving_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']		= "hemovingdetil_line";
	
	$__CONF['D']['DetilException']['TABLE_NAME']	= "transaksi_hemovingexcp";
	$__CONF['D']['DetilException']['PRIMARY_KEY1']	= "hemoving_id";
	$__CONF['D']['DetilException']['PRIMARY_KEY2']	= "hemovingdetilex_line";

	$__CONF['D']['DetilInvoice']['TABLE_NAME']		= "transaksi_hemovinginvoice";
	$__CONF['D']['DetilInvoice']['PRIMARY_KEY1']		= "hemoving_id";
	$__CONF['D']['DetilInvoice']['PRIMARY_KEY2']		= "hemovinginvoice_line";

	$__CONF['D']['DetilLogisticCost']['TABLE_NAME']			= "transaksi_hemovinglogisticcost";
	$__CONF['D']['DetilLogisticCost']['PRIMARY_KEY1']		= "hemoving_id";
	$__CONF['D']['DetilLogisticCost']['PRIMARY_KEY2']		= "hemovinglogisticcost_line";


	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_tprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_tlog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>