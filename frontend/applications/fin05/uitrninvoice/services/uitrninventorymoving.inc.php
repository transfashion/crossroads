<?php

	$__CONF['H']['TABLE_NAME'] 					= 'transaksi_inventorymoving';
	$__CONF['H']['PRIMARY_KEY'] 				= 'inventorymoving_id';
	$__CONF['H']['CREATEBY'] 					= 'inventorymoving_createby';
	$__CONF['H']['CREATEDATE'] 					= 'inventorymoving_createdate';
	$__CONF['H']['MODIFYBY'] 					= 'inventorymoving_modifyby';
	$__CONF['H']['MODIFYDATE'] 					= 'inventorymoving_modifydate';


	$__CONF['D']['DetilItem']['TABLE_NAME']		= "transaksi_inventorymovingdetil";
	$__CONF['D']['DetilItem']['PRIMARY_KEY1']	= "inventorymoving_id";
	$__CONF['D']['DetilItem']['PRIMARY_KEY2']	= "inventorymovingdetil_line";
	
	
	$__CONF['D']['DetilProduct']['TABLE_NAME']		= "transaksi_inventorymovingdetil";
	$__CONF['D']['DetilProduct']['PRIMARY_KEY1']	= "inventorymoving_id";
	$__CONF['D']['DetilProduct']['PRIMARY_KEY2']	= "inventorymovingdetil_line";

	$__CONF['D']['DetilComponent']['TABLE_NAME']	= "transaksi_inventorymovingdetil";
	$__CONF['D']['DetilComponent']['PRIMARY_KEY1']	= "inventorymoving_id";
	$__CONF['D']['DetilComponent']['PRIMARY_KEY2']	= "inventorymovingdetil_line";

		
	
	$__CONF['D']['DetilException']['TABLE_NAME']	= "transaksi_inventorymovingdetilex";
	$__CONF['D']['DetilException']['PRIMARY_KEY1']	= "inventorymoving_id";
	$__CONF['D']['DetilException']['PRIMARY_KEY2']	= "inventorymovingdetilex_line";
	
	$__CONF['D']['Prop']['TABLE_NAME']			= "transaksi_inventorymovingprop";
	$__CONF['D']['Prop']['PRIMARY_KEY1']		= "prop_id";
	$__CONF['D']['Prop']['PRIMARY_KEY2']		= "prop_line";	

	$__CONF['D']['Log']['TABLE_NAME']			= "transaksi_inventorymovinglog";
	$__CONF['D']['Log']['PRIMARY_KEY1']			= "log_id";
	$__CONF['D']['Log']['PRIMARY_KEY2']			= "log_line";	



?>