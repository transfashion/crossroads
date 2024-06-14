<?php

				$criteria = sprintf(
							    "%s='%s' AND %s='%s'", 
				                $__CONF['D'][$DETIL_NAME]['PRIMARY_KEY1'], 
								$__ID, 
								$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2'], 
								$arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']}
							);	
								
				$SQL = sprintf("SELECT * FROM %s WHERE %s",	$__CONF['D'][$DETIL_NAME]['TABLE_NAME'], $criteria);
				$rs = $conn->Execute($SQL);
				
				IF ($arrDetilData[$i]->selected) {
					if (!$rs->recordCount()) {
						$obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY1']}	= $__ID;
						$obj->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']}	= $arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']};
						$obj->rowid = uniqid();
						$SQL = SQLUTIL::SQL_InsertFromObject($__CONF['D'][$DETIL_NAME]['TABLE_NAME'], $obj);
						$conn->Execute($SQL);
					}
				} else {
					if ($rs->recordCount()) {
						$SQL = sprintf("DELETE FROM %s WHERE %s", $__CONF['D'][$DETIL_NAME]['TABLE_NAME'], $criteria);
						$conn->Execute($SQL);
					}
				}

?>