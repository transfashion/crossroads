<?php

			
			if ($__POSTDATA->H->__ROWSTATE=='NEW') {
				//$obj->{$__CONF['H']['PRIMARY_KEY']}	= GenerateID($_ID_GENERATOR_ARGS);
				$obj->{$__CONF['H']['CREATEBY']}	= $__USERNAME;
				$obj->{$__CONF['H']['CREATEDATE']} 	= SQLUTIL::SQL_GetNowDate();
				$obj->rowid = uniqid();
				$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['H']['TABLE_NAME'], $obj);
				$__ID = $obj->{$__CONF['H']['PRIMARY_KEY']};
			} else {
				$criteria = sprintf("%s='%s'", $__CONF['H']['PRIMARY_KEY'], $__ID);
				$_LOG_DIFF = SQLUTIL::GetDataDifference($conn, $__CONF['H']['TABLE_NAME'], $criteria, $obj);
				$obj->{$__CONF['H']['MODIFYBY']} 	= $__USERNAME;
				$obj->{$__CONF['H']['MODIFYDATE']} 	= SQLUTIL::SQL_GetNowDate();
				$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['H']['TABLE_NAME'], $obj, $criteria);
			}
	
			$conn->Execute($SQL);
			$__RESULT[0]->H = $obj;


			SQLUTIL::WriteToLog(
				$conn, 
				$__CONF['D']['Log']['TABLE_NAME'], 
				$__CONF['D']['Log']['PRIMARY_KEY1'],
				$__CONF['D']['Log']['PRIMARY_KEY2'],
				$__CONF['H']['TABLE_NAME'], 
				$__POSTDATA->H->__ROWSTATE, 
				$_LOG_DIFF, 
				$__USERNAME, 
				$__IP, 
				$__IPLOCAL, 
				$__COMPNAME
			);

?>