<?php

				$criteria = sprintf(
							    "%s='%s' AND %s='%s'", 
				                $__CONF['D'][$DETIL_NAME]['PRIMARY_KEY1'], 
								$__ID, 
								$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2'], 
								$arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']}
							);
							
				switch ($arrDetilData[$i]->__ROWSTATE) {
					case 'NEW' :
						$obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY1']}	= $__ID;
						$obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']}	= $arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']};
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