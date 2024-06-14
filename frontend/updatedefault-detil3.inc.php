<?php

				$criteria = sprintf(
							    "%s='%s' AND %s='%s' AND %s='%s'", 
				                $__CONF['D'][$DETIL_NAME]['PRIMARY_KEY1'], 
								$__ID, 
								$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2'], 
								$arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']},
								$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY3'], 
								$arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY3']}								
							);
							
				switch ($arrDetilData[$i]->__ROWSTATE) {
					case 'NEW' :
						$obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY1']}	= $__ID;
						
						if (!isset($obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']})) {
							$obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']}	= $arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']};
						}
						
						if (!isset($obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY3']})) {
							$obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY3']}	= $arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY3']};
						}
						
						$obj->rowid = uniqid();
						$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D'][$DETIL_NAME]['TABLE_NAME'], $obj);					
						break;
						
					case 'UPDATE' :
						$SQL = SQLUTIL::SQL_UpdateFromObject($__CONF['D'][$DETIL_NAME]['TABLE_NAME'], $obj, $criteria);
						break;
						
					case 'DELETE' :
						$SQL = sprintf("DELETE FROM %s WHERE %s", $__CONF['D'][$DETIL_NAME]['TABLE_NAME'], $criteria);
						break;		
				}
				
				$conn->Execute($SQL);

?>