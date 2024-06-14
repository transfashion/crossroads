<?php

		/* Update Header */
		if (!empty($__POSTDATA->H)) {
		
			unset($obj);
			$obj->customer_title		= $__POSTDATA->H->customer_title;
			$obj->customer_namefull		= $__POSTDATA->H->customer_namefull;
			$obj->customer_namenick		= $__POSTDATA->H->customer_namenick;
			$obj->customer_address		= $__POSTDATA->H->customer_address;
			$obj->customer_city			= $__POSTDATA->H->customer_city;
			$obj->customer_postcode		= $__POSTDATA->H->customer_postcode;
			$obj->customer_provincy		= $__POSTDATA->H->customer_provincy;
			$obj->customer_country		= $__POSTDATA->H->customer_country;
			$obj->customer_email		= $__POSTDATA->H->customer_email;
			$obj->customer_sizetop		= $__POSTDATA->H->customer_sizetop;
			$obj->customer_sizebottom	= $__POSTDATA->H->customer_sizebottom;
			$obj->customer_sizeshoes	= $__POSTDATA->H->customer_sizeshoes;
			$obj->customer_birthdate	= $__POSTDATA->H->customer_birthdate;
			$obj->customer_withbirthdate	= $__POSTDATA->H->customer_withbirthdate;
			//$obj->customer_phonehome	= $__POSTDATA->H->customer_phonehome;
			//$obj->customer_phonework	= $__POSTDATA->H->customer_phonework;
			//$obj->occupation_id			= $__POSTDATA->H->occupation_id;
			$obj->gender_id				= $__POSTDATA->H->customer_gender;
			//$obj->customertype_id		= $__POSTDATA->H->customertype_id;
			//$obj->rekanan_id			= $__POSTDATA->H->rekanan_id;
			
			//$obj->region_id				= $__POSTDATA->H->region_id;
			//$obj->branch_id				= $__POSTDATA->H->branch_id;
			//$obj->buy					= $__POSTDATA->H->buy;
			//$obj->qty					= $__POSTDATA->H->qty;
			//$obj->date					= $__POSTDATA->H->date;
			
	
			$_ID_GENERATOR_ARGS = array();
			require dirname(__FILE__).'/../../../../updatedefault-header.inc.php';
		}
		


		function GenerateID($_ID_GENERATOR_ARGS) {
			return time();
		}		
	
?>