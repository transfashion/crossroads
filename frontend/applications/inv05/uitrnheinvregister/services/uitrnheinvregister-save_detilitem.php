<?php
/*
Generated by TransBrowser Generator
Created by dhewe, 21/03/2011 15:55
Program untuk registrasi item
Filename: uitrnheinvregister-save_detilitem.php
*/

/*
Dimodifikasi:
Agung, 2018-11-05, barcode harus diisi
 */


		$DETIL_NAME = "DetilItem";
		$arrDetilData = $__POSTDATA->D->{$DETIL_NAME};
		if (is_array($arrDetilData)) {
			for ($i=0; $i<count($arrDetilData); $i++) {
				unset($obj);
				//$obj->heinv_id 			= (string) SQLUTIL::Normal($arrDetilData[$i]->heinv_id);

				$obj->heinv_art 		= (string) SQLUTIL::Normal(trim($arrDetilData[$i]->heinv_art));
				$obj->heinv_mat 		= (string) SQLUTIL::Normal(trim($arrDetilData[$i]->heinv_mat));
				$obj->heinv_col 		= (string) SQLUTIL::Normal(trim($arrDetilData[$i]->heinv_col));
				$obj->heinv_size 		= (string) SQLUTIL::Normal(trim($arrDetilData[$i]->heinv_size));
				$obj->heinv_barcode		= (string) SQLUTIL::Normal(trim($arrDetilData[$i]->heinv_barcode)); 
				$obj->heinv_name 		= substr((string) trim($arrDetilData[$i]->heinv_name),0,40);
				$obj->heinv_descr 		= substr((string) trim($arrDetilData[$i]->heinv_descr),0,50);
				$obj->heinv_box			= substr((string) trim($arrDetilData[$i]->heinv_box),0,50);
				$obj->heinv_gtype 		= trim($arrDetilData[$i]->heinv_gtype);
				
				if ($obj->heinv_size=='') {
					$dataline = $arrDetilData[$i]->{$__CONF['D'][$DETIL_NAME]['PRIMARY_KEY2']};
					throw new Exception("Size belum diisi pada baris ke $dataline");
				}				
								
				$obj->heinv_produk 		= trim($arrDetilData[$i]->heinv_produk);
				$obj->heinv_bahan 		= (string) substr(SQLUTIL::Normal(trim($arrDetilData[$i]->heinv_bahan)),0,69);
				$obj->heinv_pemeliharaan= (string) substr(SQLUTIL::Normal(trim($arrDetilData[$i]->heinv_pemeliharaan)),0,99);
				$obj->heinv_logo 		= trim($arrDetilData[$i]->heinv_logo);
				$obj->heinv_dibuatdi 	= trim($arrDetilData[$i]->heinv_dibuatdi);
				$obj->heinv_other1 		= trim($arrDetilData[$i]->heinv_other1);
				// $obj->heinv_other2 		= trim($arrDetilData[$i]->heinv_other2);
				// $obj->heinv_other3 		= trim($arrDetilData[$i]->heinv_other3);
				// $obj->heinv_other4 		= trim($arrDetilData[$i]->heinv_other4);
				// $obj->heinv_other5 		= trim($arrDetilData[$i]->heinv_other5);
				$obj->heinv_other2 		= substr((string) trim($arrDetilData[$i]->heinv_pline),0,50);
				$obj->heinv_other3 		= substr((string) trim($arrDetilData[$i]->heinv_fit),0,50);
				$obj->heinv_other4 		= substr((string) trim($arrDetilData[$i]->heinv_pgroup),0,50);
				$obj->heinv_other5 		= substr((string) trim($arrDetilData[$i]->heinv_pcategory),0,50);
				$obj->heinv_other6 		= substr((string) trim($arrDetilData[$i]->heinv_colordescr),0,30);
				$obj->heinv_other7 		= substr((string) trim($arrDetilData[$i]->heinv_gender),0,1);
				

				$obj->heinv_hscode_ina 	    = substr((string) trim($arrDetilData[$i]->heinv_hscode_ina),0,50);
				$obj->heinv_hscode_ship 	= substr((string) trim($arrDetilData[$i]->heinv_hscode_ship),0,50);
				$obj->heinv_plbname 		= substr((string) trim($arrDetilData[$i]->heinv_plbname),0,100);

				
				$obj->heinv_isweb 		= trim($arrDetilData[$i]->heinv_isweb);
				$obj->heinv_weight 		= trim($arrDetilData[$i]->heinv_weight);
				$obj->heinv_length 		= trim($arrDetilData[$i]->heinv_length);
				$obj->heinv_width 		= trim($arrDetilData[$i]->heinv_width);
				$obj->heinv_height 		= trim($arrDetilData[$i]->heinv_height);
				$obj->heinv_webdescr 		= trim($arrDetilData[$i]->heinv_webdescr);

				$obj->heinv_price 		= trim($arrDetilData[$i]->heinv_price);
				//$obj->heinvgro_id 		= $arrDetilData[$i]->heinvgro_id;
				$obj->heinvctg_id 		= trim($arrDetilData[$i]->heinvctg_id);
				//$obj->heinvctg_sizetag  = $arrDetilData[$i]->heinvctg_sizetag;
				$obj->branch_id 		= (string) SQLUTIL::Normal(trim($arrDetilData[$i]->branch_id));				
				$obj->C00 = $arrDetilData[$i]->C00;
				$obj->C01 = $arrDetilData[$i]->C01;
				$obj->C02 = $arrDetilData[$i]->C02;
				$obj->C03 = $arrDetilData[$i]->C03;
				$obj->C04 = $arrDetilData[$i]->C04;
				$obj->C05 = $arrDetilData[$i]->C05;
				$obj->C06 = $arrDetilData[$i]->C06;
				$obj->C07 = $arrDetilData[$i]->C07;
				$obj->C08 = $arrDetilData[$i]->C08;
				$obj->C09 = $arrDetilData[$i]->C09;
				$obj->C10 = $arrDetilData[$i]->C10;
				$obj->C11 = $arrDetilData[$i]->C11;
				$obj->C12 = $arrDetilData[$i]->C12;
				$obj->C13 = $arrDetilData[$i]->C13;
				$obj->C14 = $arrDetilData[$i]->C14;
				$obj->C15 = $arrDetilData[$i]->C15;
				$obj->C16 = $arrDetilData[$i]->C16;
				$obj->C17 = $arrDetilData[$i]->C17;
				$obj->C18 = $arrDetilData[$i]->C18;
				$obj->C19 = $arrDetilData[$i]->C19;
				$obj->C20 = $arrDetilData[$i]->C20;
				$obj->C21 = $arrDetilData[$i]->C21;
				$obj->C22 = $arrDetilData[$i]->C22;
				$obj->C23 = $arrDetilData[$i]->C23;
				$obj->C24 = $arrDetilData[$i]->C24;
				$obj->C25 = $arrDetilData[$i]->C25;
				
				
				
				if (trim($obj->heinv_barcode)=='') {
					throw new Exception("per 2018 November, Semua Barcode harus diisi!, cek artikel " . $obj->heinv_art);  
				}
				
				
				require dirname(__FILE__).'/uitrnheinvregister-save_detilitem_lookup.php';
				
				
				
				$_MODIFIED = true; 
				require dirname(__FILE__).'/../../../../updatedefault-detil.inc.php';				
			}
		}


?>