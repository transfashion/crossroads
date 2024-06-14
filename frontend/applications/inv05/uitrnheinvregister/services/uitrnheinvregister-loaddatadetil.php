<?php
if (!defined('__SERVICE__')) {
	die("access denied");
}

		$username 	= $_SESSION["username"];
		$criteria	= $_POST['criteria'];
		
		
		$SQL_CRITERIA = "";
		$objCriteria = json_decode(stripslashes($criteria));
		if (is_array($objCriteria)) {
			$criteria = array();
			while (list($name, $value) = each($objCriteria)) {
				$criteria[$value->name] = $value;
				//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
			}
			
			/* Default Criteria */
			/*
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemovingtype_id', 'hemovingtype_id', " %s = '%s' ");
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_id', 'hemoving_id', "refParser");
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
			SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_hemoving_descr', 'hemoving_descr', " {db_field} LIKE '%{criteria_value}%' ");
			*/
		
		
			$heinvregister_id = SQLUTIL::BuildCriteria(&$param, $criteria, 'heinvregister_id', '', "{criteria_value}");
			$offset =  SQLUTIL::BuildCriteria(&$param, $criteria, 'offset', '', "{criteria_value}");
		
		}		
		
		
	
	$sql = "select * from transaksi_heinvregister where heinvregister_id='$heinvregister_id'";
	$rs  = $conn->Execute($sql);
	unset($objh);
	$objh->region_id = trim($rs->fields['region_id']);
	$objh->branch_id = trim($rs->fields['branch_id']);	
	$objh->season_id = trim($rs->fields['season_id']);
	$objh->heinvregister_issizing = trim($rs->fields['heinvregister_issizing']);
		
		
	$sql = "select * from transaksi_heinvregisteritem where heinvregister_id='$heinvregister_id' order by heinvregisteritem_line";
	$rs  = $conn->SelectLimit($sql, 100, $offset);
	$data = array();
	while (!$rs->EOF) {
		unset($obj);

		$obj->heinvregister_id = (string) trim($rs->fields['heinvregister_id']);
		$obj->heinvregisteritem_line = (float) trim($rs->fields['heinvregisteritem_line']);
		$obj->heinv_art = (string) trim($rs->fields['heinv_art']);
		$obj->heinv_mat = (string) trim($rs->fields['heinv_mat']);
		$obj->heinv_col = (string) trim($rs->fields['heinv_col']);
		$obj->heinv_size = (string) trim($rs->fields['heinv_size']);
		$obj->heinv_barcode = (string) trim($rs->fields['heinv_barcode']);
		$obj->heinv_name = (string) trim($rs->fields['heinv_name']);
		$obj->heinv_descr = (string) trim($rs->fields['heinv_descr']);
		$obj->heinv_box = (string) trim($rs->fields['heinv_box']);
		$obj->heinv_gtype = (string) trim($rs->fields['heinv_gtype']);
		$obj->heinv_produk = (string) trim($rs->fields['heinv_produk']);
		$obj->heinv_bahan = (string) trim($rs->fields['heinv_bahan']);
		$obj->heinv_pemeliharaan = (string) trim($rs->fields['heinv_pemeliharaan']);
		$obj->heinv_logo = (string) trim($rs->fields['heinv_logo']);
		$obj->heinv_dibuatdi = (string) trim($rs->fields['heinv_dibuatdi']);
		$obj->heinv_other1 = (string) trim($rs->fields['heinv_other1']);
		$obj->heinv_other2 = (string) trim($rs->fields['heinv_other2']);
		$obj->heinv_other3 = (string) trim($rs->fields['heinv_other3']);
		$obj->heinv_other4 = (string) trim($rs->fields['heinv_other4']);
		$obj->heinv_other5 = (string) trim($rs->fields['heinv_other5']);
		$obj->heinv_pline = (string) trim($rs->fields['heinv_other2']);
		$obj->heinv_fit = (string) trim($rs->fields['heinv_other3']);

		$obj->heinv_hscode_ship = (integer) trim($rs->fields['heinv_hscode_ship']);
		$obj->heinv_hscode_ina = (integer) trim($rs->fields['heinv_hscode_ina']);

		$obj->heinv_plbname = (string) trim($rs->fields['heinv_plbname']);

		$obj->heinv_isweb = (integer) trim($rs->fields['heinv_isweb']);
		$obj->heinv_weight = (float) trim($rs->fields['heinv_weight']);
		$obj->heinv_length = (float) trim($rs->fields['heinv_length']);
		$obj->heinv_width = (float) trim($rs->fields['heinv_width']);
		$obj->heinv_height = (float) trim($rs->fields['heinv_height']);
		$obj->heinv_webdescr = (string) trim($rs->fields['heinv_webdescr']);



		$obj->heinv_pgroup = (string) trim($rs->fields['heinv_other4']);
		$obj->heinv_pcategory = (string) trim($rs->fields['heinv_other5']);
		$obj->heinv_colordescr = (string) trim($rs->fields['heinv_other6']);
		$obj->heinv_gender = (string) trim($rs->fields['heinv_other7']);
		$obj->heinv_price = (float) trim($rs->fields['heinv_price']);
		$obj->heinvctg_id = (string) trim($rs->fields['heinvctg_id']);
		$register_heinvctg_id = $obj->heinvctg_id;
		$register_heinvctg_beda = false;


		$obj->region_id = (string) trim($objh->region_id);
		$obj->branch_id = (string) trim($rs->fields['branch_id']);
		$obj->branch_name = (string) SQLUTIL::GetRsFromTableById("master_branch", "branch_id", $obj->branch_id, $conn)->fields['branch_name'];
		$obj->heinv_id = (string) (trim($rs->fields['heinv_id']) ? trim($rs->fields['heinv_id']) : SQLUTIL::GetRsHeinvFromTableByKey($obj->heinv_art, $obj->heinv_mat, $obj->heinv_col, $objh->season_id, $obj->region_id, $conn)->fields['heinv_id']);

		if ($obj->heinv_id) {
			// kalau ada heinv_id nya, data2 nya di ovverride dari heinv_id nya 
			$sql = " select heinvctg_id, region_id from master_heinv where heinv_id='".$obj->heinv_id."' ";
			$rsI = $conn->Execute($sql);
			
			$current_heinvctg_id = (string) trim($rsI->fields['heinvctg_id']);
			$master_heinvctg_id = (string) trim($rsI->fields['heinvctg_id']);
			$obj->region_id   = (string) trim($rsI->fields['region_id']);

			if ($obj->heinv_hscode_ina==0) {
				$obj->heinv_hscode_ina  = (integer) trim($rsI->fields['heinv_hscode_ina']);
			}

			if ($obj->heinv_plbname == "") {
				$obj->heinv_plbname = (string) trim($rsI->fields['heinv_plbname']);	
			}  	
			
			if ($register_heinvctg_id!=$master_heinvctg_id) {
				$register_heinvctg_beda = true;
			}

		} else {
			$current_heinvctg_id = $obj->heinvctg_id;
		} 

		$sql = "select heinvctg_name, heinvctg_sizetag, heinvgro_id, heinvctg_isdisabled, heinvctg_isinactive from master_heinvctg where heinvctg_id='$current_heinvctg_id' and region_id='$obj->region_id' ";
		$rsI = $conn->Execute($sql);
		$obj->heinvctg_name = (string) trim($rsI->fields['heinvctg_name']);		
		if ($register_heinvctg_beda) {
			$obj->heinvctg_name = "** " . $obj->heinvctg_name;
		}		
		
		$obj->heinvctg_sizetag = (string) trim($rsI->fields['heinvctg_sizetag']);						
		$obj->heinvgro_id = (string) trim($rsI->fields['heinvgro_id']);	
		$obj->heinvctg_isdisabled = (string) trim($rsI->fields['heinvctg_isdisabled']);
		$obj->heinvctg_isinactive = (string) trim($rsI->fields['heinvctg_isinactive']);
		
		
		$sql = "select heinvgro_name from master_heinvgro where heinvgro_id='$obj->heinvgro_id' and region_id='$obj->region_id' ";
		$rsI = $conn->Execute($sql);
		$obj->heinvgro_name = (string) trim($rsI->fields['heinvgro_name']);						


		$obj->C00 = (float) trim($rs->fields['C00']);
		$obj->C01 = (float) trim($rs->fields['C01']);
		$obj->C02 = (float) trim($rs->fields['C02']);
		$obj->C03 = (float) trim($rs->fields['C03']);
		$obj->C04 = (float) trim($rs->fields['C04']);
		$obj->C05 = (float) trim($rs->fields['C05']);
		$obj->C06 = (float) trim($rs->fields['C06']);
		$obj->C07 = (float) trim($rs->fields['C07']);
		$obj->C08 = (float) trim($rs->fields['C08']);
		$obj->C09 = (float) trim($rs->fields['C09']);
		$obj->C10 = (float) trim($rs->fields['C10']);
		$obj->C11 = (float) trim($rs->fields['C11']);
		$obj->C12 = (float) trim($rs->fields['C12']);
		$obj->C13 = (float) trim($rs->fields['C13']);
		$obj->C14 = (float) trim($rs->fields['C14']);
		$obj->C15 = (float) trim($rs->fields['C15']);
		$obj->C16 = (float) trim($rs->fields['C16']);
		$obj->C17 = (float) trim($rs->fields['C17']);
		$obj->C18 = (float) trim($rs->fields['C18']);
		$obj->C19 = (float) trim($rs->fields['C19']);
		$obj->C20 = (float) trim($rs->fields['C20']);
		$obj->C21 = (float) trim($rs->fields['C21']);
		$obj->C22 = (float) trim($rs->fields['C22']);
		$obj->C23 = (float) trim($rs->fields['C23']);
		$obj->C24 = (float) trim($rs->fields['C24']);
		$obj->C25 = (float) trim($rs->fields['C25']);
		$obj->rowid = (string) trim($rs->fields['rowid']);
		
		
		$ERRCODE = 0;
		
		
		// CEK HSCODE
		//if ($obj->heinv_hscode_ina<10000000) {
		//	$ERRCODE += 7000000;
		//}

		if ($obj->heinv_hscode_ship < 10000000) {
			$ERRCODE += 6500000;
		} else if ($obj->heinv_hscode_ina < 10000000) {
			$ERRCODE += 7000000;		 
		}	


		// Cek apakah menggunakan grouping baru
		// HBS00A10
		if ($obj->heinvctg_isdisabled!='0') {
			$ERRCODE += 600000;
		}
		
		if (substr($obj->heinvctg_id, 3, 2)!='00') {
			$ERRCODE += 50000;
		}

		if ($register_heinvctg_beda) {
			$ERRCODE += 45000;
		}		

		// Cek Group dan Category, apabila GROUP sudah terisi, otomatis ctg terisi
		if (!$obj->heinvgro_id || !$obj->heinvgro_name) {
			$ERRCODE += 4000;
		} else {
			$ERRCODE += 0;
		}		
		
		if ($objh->heinvregister_issizing) {
			//jika sizing, cek apakah kode SIZE terdaftar sesuai dengan sizetagnya
			$sql = "SELECT * FROM master_heinvsizetag WHERE SIZETAG='$obj->heinvctg_sizetag' and region_id='$obj->region_id'";
			
			
			$rsI = $conn->Execute($sql);
			if (!$rsI->recordCount()) {
				$ERRCODE += 100;
			}
			
			//cek apakah sizenya cocok
			$sizefound = false;
			for ($iC=1; $iC<=25; $iC++) {
				$colname = "C" . str_pad($iC, 2, "0", STR_PAD_LEFT);
				if (trim($rsI->fields[$colname])==trim($obj->heinv_size)) {
					$sizefound = true;
					break;					
				}
			}
			
			if (!$sizefound) {
				//cek lagi di sizetag shadownya
				$sql = "SELECT * FROM master_heinvsizetagshadow WHERE SIZETAG='$obj->heinvctg_sizetag' and region_id='$obj->region_id'";
				$rsI = $conn->Execute($sql);
				if (!$rsI->recordCount()) {
					$ERRCODE += 100;
				}				

				$sizefound = false;
				for ($iC=1; $iC<=25; $iC++) {
					$colname = "C" . str_pad($iC, 2, "0", STR_PAD_LEFT);
					if (trim($rsI->fields[$colname])==trim($obj->heinv_size)) {
						$sizefound = true;
						break;					
					}
				}
					
				if (!$sizefound) {
					$ERRCODE += 10;
				}		
			}
			
		
		}
		
		
		//cek branch nya
		if (!$obj->branch_name) {
			$ERRCODE += 1;
		}
		
		
		
		
		
		
		$obj->err = $ERRCODE;
		
		//validasi item
		/*
		if ($objh->heinvregister_issizing) {
			$obj->err = 19999;
		} else {
			if (!$obj->heinvgro_id || !$obj->heinvgro_name) {
				$obj->err = 10000;
			} else {
				$obj->err = 0;
			}
		}
		*/
		
		$data[] = $obj;
		$rs->MoveNext();
	}		
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = $totalCount;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));


		




?>